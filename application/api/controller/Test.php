<?php

namespace app\api\controller;

use app\extra\zgysbase\JWT;
use think\Controller;

class Test extends Controller
{

    public static   $key  = "";
    /**
     * 创建token
     * @param $account 账户
     * @param $type 账户类型
     * @return \think\response\Json
     */
    public function encode()
    {
        $arr = input();
        $base64 = base64_encode(json_encode($arr));
        print_r($base64);
        die;
    }

    public function decode()
    {
        $arr = input();
        $base64 = json_decode(base64_decode($arr['row']));
        echo "<pre>";
        print_r($base64);
        die;
    }

    
}
