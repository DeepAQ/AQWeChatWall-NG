<?php
/**
 * Created by PhpStorm.
 * User: adn55
 * Date: 16/10/20
 * Time: 13:59
 */

namespace app\index\controller;


use app\index\model\MessageModel;
use app\index\model\WechatUserModel;
use app\index\Utils;
use think\Controller;

class Message extends Controller
{
    public function get($wallid = -1, $offset = 0, $limit = 10, $poll = false)
    {
        $wallid = Utils::checkWallId($wallid);
        if ($wallid <= 0) {
            return json(['success' => false, 'error' => '参数不正确']);
        }
        // start getting/polling messages
        $start_time = time();
        do {
            if ($poll && time()-$start_time>=60) {
                break;
            }
            $list = MessageModel::where('m.wallid', $wallid)
                ->where('m.id', '>', $offset)
                ->alias('m')
                ->join('wall_user u', 'u.openid = m.openid')
                ->field(['m.id', 'm.content', 'u.nickname', 'u.sex', 'u.headimgurl'])
                ->order('m.id desc')
                ->limit($limit)
                ->select();
        } while ($poll && sizeof($list) == 0 && sleep(1) == 0);
        // prepare results
        $result = [];
        foreach ($list as $message) {
            $result[] = [
                'id' => $message->id,
                'content' => htmlspecialchars($message->content),
                'headimgurl' => !empty($message->headimgurl) ? $message->headimgurl : config('static_path').'/nohead.jpg',
                'nickname' => htmlspecialchars($message->nickname),
                'sex' => $message->sex,
            ];
        }
        return json(['success' => true, 'messages' => $result]);
    }

    public function post($wallid = -1)
    {
        $wallid = Utils::checkWallId($wallid);
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