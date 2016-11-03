<?php
/**
 * Created by PhpStorm.
 * User: adn55
 * Date: 16/10/23
 * Time: 22:31
 */

namespace app\index\controller;


use app\index\model\MessageModel;
use app\index\model\WallConfigModel;
use app\index\model\WechatUserModel;
use think\Controller;
use think\Session;

class Manage extends Controller
{
    public function index()
    {
        $this->checkLogin();
        $this->redirect('/manage/wall');
    }

    public function login()
    {
        if (Session::has('manage_login')) {
            $this->redirect('/manage');
        } else {
            if ($this->request->post('password')) {
                if ($this->request->post('password') == config('manage_password')) {
                    Session::set('manage_login', true);
                    return $this->redirect('/manage');
                } else {
                    $this->assign('login_failed', true);
                }
            }
            return $this->fetchTemplate('manage/login');
        }
    }

    public function wall()
    {
        $this->checkLogin();
        switch ($this->request->get('op')) {
            case 'toggle':
                $wall = WallConfigModel::get($this->request->post('wallid'));
                $this->assign('op_result', false);
                if ($wall) {
                    $wall->active = 1 - $wall->active;
                    if ($wall->save()) {
                        $this->assign('op_result', true);
                    }
                }
                break;
            case 'add':
                $result = WallConfigModel::create([
                    'name' => $this->request->post('name'),
                    'background' => $this->request->post('background'),
                ]);
                if ($result) {
                    $this->assign('op_result', true);
                } else {
                    $this->assign('op_result', false);
                }
                break;
        }
        $this->assign('list', WallConfigModel::all(function ($query) {
            $query->order('id desc');
        }));
        return $this->fetchTemplate('manage/wall');
    }

    public function user()
    {
        $this->checkLogin();
        $key = $this->request->get('search');
        if (!empty($key)) {
            $this->assign('list', WechatUserModel::where('nickname', 'like', "%$key%")
                    ->whereOr('openid', 'like', $key)
                    ->paginate(15, null, ['query' => ['search' => $key]])
            );
        } else {
            $this->assign('list', WechatUserModel::paginate(15));
        }
        return $this->fetchTemplate('manage/user');
    }

    public function message($wallid)
    {
        $this->checkLogin();
        $wall = WallConfigModel::get($wallid);
        if (empty($wall)) {
            return $this->redirect('/manage/wall');
        }
        $this->assign('wall', $wall);
        $this->assign('list', MessageModel::where('wallid', $wallid)
            ->alias('m')
            ->join('wall_user u', 'u.openid = m.openid')
            ->order('m.id desc')
            ->paginate(15)
        );
        return $this->fetchTemplate('manage/message');
    }

    public function userlist($wallid)
    {
        $this->checkLogin();
        $wall = WallConfigModel::get($wallid);
        if (empty($wall)) {
            return $this->redirect('/manage/wall');
        }
        $this->assign('wall', $wall);
        $this->assign('list', MessageModel::where('m.wallid', $wallid)
            ->alias('m')
            ->join('wall_user u', 'u.openid = m.openid')
            ->field('u.nickname')
            ->distinct(true)
            ->select()
        );
        return $this->fetchTemplate('manage/userlist');
    }

    private function checkLogin()
    {
        if (!Session::has('manage_login')) {
            $this->redirect('/manage/login');
        }
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