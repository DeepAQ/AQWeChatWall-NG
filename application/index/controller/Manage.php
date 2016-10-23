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

class Manage extends Controller
{
    public function index()
    {
        $this->view->replace([
            '__STATIC__' => config('static_path'),
            '__TITLE__' => config('app_title'),
        ]);
        $this->assign('list',
            WallConfigModel::all()
        );
        return $this->fetch('manage/wall');
    }
}