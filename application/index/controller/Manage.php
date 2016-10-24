<?php
/**
 * Created by PhpStorm.
 * User: adn55
 * Date: 16/10/23
 * Time: 22:31
 */

namespace app\index\controller;


use app\index\model\WallConfigModel;
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
                $this->assign('op_result', 2);
                if ($wall) {
                    $wall->active = 1 - $wall->active;
                    if ($wall->save()) {
                        $this->assign('op_result', 1);
                    }
                }
                break;
            case 'add':
                $result = WallConfigModel::create([
                    'name' => $this->request->post('name'),
                    'background' => $this->request->post('background'),
                ]);
                if ($result) {
                    $this->assign('op_result', 1);
                } else {
                    $this->assign('op_result', 2);
                }
                break;
        }
        $this->assign('list', WallConfigModel::all(function ($query) {
            $query->order('id desc');
        }));
        return $this->fetchTemplate('manage/wall');
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