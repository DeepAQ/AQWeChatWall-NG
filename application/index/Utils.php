<?php
/**
 * Created by PhpStorm.
 * User: adn55
 * Date: 16/10/20
 * Time: 20:08
 */

namespace app\index;


use app\index\model\WallConfigModel;

class Utils
{
    public static function getUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if (substr($url, 0, 5) == 'https'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        $s = curl_exec($ch);
        curl_close($ch);
        return $s;
    }

    public static function checkWallId($wallid = -1)
    {
        if ($wallid <= 0) {
            $list = WallConfigModel::where('active', 1)->order('id desc')->select();
            if (sizeof($list) == 0) {
                return -1;
            } else {
                return $list[0]->id;
            }
        } else {
            $count = WallConfigModel::where('id', $wallid)->where('active', 1)->count();
            if ($count == 1) {
                return $wallid;
            } else {
                return -1;
            }
        }
    }
}