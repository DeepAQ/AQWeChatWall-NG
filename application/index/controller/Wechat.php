<?php
/**
 * Created by PhpStorm.
 * User: adn55
 * Date: 16/10/20
 * Time: 18:36
 */

namespace app\index\controller;


use app\index\model\WechatUserModel;
use app\index\Utils;
use think\Controller;
use think\Session;

class Wechat extends Controller
{
    public function login()
    {
        $appid = config('wechat_appid');
        $redirectUri = urlencode('http://mebox.top/weixin/wechatwall');
        $this->redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirectUri&response_type=code&scope=snsapi_userinfo#wechat_redirect");
    }

    public function authcallback($code)
    {
        // get access_token
        $appid = config('wechat_appid');
        $secret = config('wechat_secret');
        $accessJson = json_decode(Utils::getUrl("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code"), true);
        if (!$accessJson || isset($accessJson['errcode'])) {
            $this->error('微信服务繁忙, 请稍后再试', '/', '', 10);
        }
        $openid = $accessJson['openid'];
        $accessToken = $accessJson['access_token'];

        // get user info
        $userJson = json_decode(Utils::getUrl("https://api.weixin.qq.com/sns/userinfo?access_token=$accessToken&openid=$openid&lang=zh_CN"), true);
        if (!$userJson || isset($userJson['errcode'])) {
            $this->error('微信服务繁忙, 请稍后再试', '/', '', 10);
        }

        // update user info
        $user = WechatUserModel::get($openid);
        if ($user) {
            $user->delete();
        }
        WechatUserModel::create([
            'openid' => $openid,
            'nickname' => $userJson['nickname'],
            'sex' => $userJson['sex'],
            'province' => $userJson['province'],
            'city' => $userJson['city'],
            'country' => $userJson['country'],
            'headimgurl' => $userJson['headimgurl'],
        ]);

        // redirect
        Session::set('openid', $openid);
        $this->redirect('/');
    }
}