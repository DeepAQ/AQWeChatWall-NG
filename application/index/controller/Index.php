<?php
namespace app\index\controller;

use app\index\model\MessageModel;
use app\index\model\WallConfigModel;
use app\index\model\WechatUserModel;
use app\index\Utils;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->redirect("/m");
    }

    public function mobile($wallid = -1)
    {
        $wallid = Utils::checkWallId($wallid);
        if ($wallid <= 0) {
            $this->error('微信墙活动不在进行中', '/');
        }
        $openid = $this->request->session('openid');
        if (!$openid || !WechatUserModel::get($openid)) {
            return $this->redirect('/wechat/login');
        } else {
            $this->assign('wall', WallConfigModel::get($wallid));
            $this->assign('list',
                MessageModel::where('openid', $openid)
                    ->where('wallid', $wallid)
                    ->order('time desc, id desc')
                    ->limit(10)
                    ->select()
            );
            return $this->fetchTemplate('index/mobile');
        }
    }

    public function screen($wallid = -1)
    {
        $wallid = Utils::checkWallId($wallid);
        if ($wallid <= 0) {
            $this->error('微信墙活动不在进行中', '/');
        }
        $this->assign('wall', WallConfigModel::get($wallid));
        return $this->fetchTemplate('index/screen');
    }

    public function luckydraw($wallid = -1)
    {
        $wallid = Utils::checkWallId($wallid);
        if ($wallid <= 0) {
            $this->error('微信墙活动不在进行中', '/');
        }
        $user_list = MessageModel::where('m.wallid', $wallid)
            ->alias('m')
            ->join('wall_user u', 'u.openid = m.openid')
            ->field('u.nickname')
            ->distinct(true)
            ->select();
        $result = [];
        foreach ($user_list as $wall_user) {
            $result[] = htmlspecialchars($wall_user->nickname);
        }
        $this->assign('wall', WallConfigModel::get($wallid));
        $this->assign('userlist', json_encode($result));
        $this->assign('usertotal', sizeof($result));
        return $this->fetchTemplate('index/luckydraw');
    }

    private function fetchTemplate($template)
    {
        $this->view->replace([
            '__STATIC__' => config('static_path'),
            '__TITLE__' => config('app_title'),
        ]);
        return $this->fetch($template);
    }
}
