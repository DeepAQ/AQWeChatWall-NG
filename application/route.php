<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'id' => '\d+',
        'name' => '\w+',
    ],
    '' => 'index/index/index',
    'message/get' => 'index/message/get',
    'message/post' => 'index/message/post',
    'wechat/login' => 'index/wechat/login',
    'wechat/authcallback' => 'index/wechat/authcallback',
];
