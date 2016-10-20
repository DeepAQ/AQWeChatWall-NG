<?php
namespace app\index\controller;

use app\index\model\MessageModel;
use app\index\model\WallConfigModel;
use app\index\Utils;
use think\Controller;

class Index extends Controller
{
    public function index($wallid = -1)
    {
        $wallid = Utils::checkWallId();
        if ($wallid <= 0) {
            $this->error('微信墙活动不在进行中', '/');
        }
        $openid = $this->request->session('openid');
        if (!$openid) {
            $this->redirect('/wechat/login');
        } else {
            $this->view->replace([
                '__STATIC__' => config('static_path')
            ]);
            $this->assign('wall', WallConfigModel::get($wallid));
            $this->assign('list',
                MessageModel::where('openid', $openid)
                    ->where('wallid', $wallid)
                    ->order('time desc, id desc')
                    ->limit(10)
                    ->select()
            );
            return $this->fetch('index/mobile');
        }
    }
}
