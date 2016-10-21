<?php
/**
 * Created by PhpStorm.
 * User: adn55
 * Date: 16/10/20
 * Time: 13:59
 */

namespace app\index\controller;


use app\index\model\MessageModel;
use app\index\Utils;
use think\Controller;

class Message extends Controller
{
    public function get($wallid = -1, $count = 10)
    {
        $wallid = Utils::checkWallId();
        if ($wallid <= 0) {
            return json(['success' => false, 'error' => '参数不正确']);
        }
        $list = MessageModel::where('wallid', $wallid)
            ->field(['time', 'content'])
            ->order('time desc')
            ->limit($count)
            ->select();
        return json($list);
    }

    public function post($wallid = -1)
    {
        $wallid = Utils::checkWallId();
        $openid = $this->request->session('openid');
        if (!$openid) {
            return json(['success' => false, 'error' => '未登录', 'relogin' => true]);
        }
        // check content
        $content = trim($this->request->post('content'));
        if ($wallid <= 0 || empty($content)) {
            return json(['success' => false, 'error' => '参数不正确']);
        }
        if (mb_strlen($content) > 200) {
            return json(['success' => false, 'error' => '内容有点长，请缩短后再发送']);
        }
        // add message
        $result = MessageModel::create([
            'wallid' => $wallid,
            'time' => time(),
            'ip' => $this->request->ip(),
            'openid' => $openid,
            'content' => $content,
        ]);
        // return
        if ($result) {
            return json(['success' => true]);
        } else {
            return json(['success' => false, 'error' => '微信墙繁忙, 请稍后再尝试发送']);
        }
    }
}