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
        $this->assign('list', WallConfigModel::all());
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