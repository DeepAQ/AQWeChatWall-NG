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
        'wallid' => '\d+',
    ],

    '/[:wallid]$' => 'index/index/mobile',
    'm/[:wallid]$' => 'index/index/mobile',
    'screen/[:wallid]$' => 'index/index/screen',
    'screen/[:wallid]/luckydraw$' => 'index/index/luckydraw',

    'message/get' => 'index/message/get',
    'message/post' => 'index/message/post',
    'wechat/login' => 'index/wechat/login',
    'wechat/authcallback' => 'index/wechat/authcallback',

    'manage/$' => 'index/manage/index',
    'manage/login' => 'index/manage/login',
    'manage/wall' => 'index/manage/wall',
    'manage/user' => 'index/manage/user',
    'manage/message/:wallid$' => 'index/manage/message',
];
