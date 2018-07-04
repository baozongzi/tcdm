<?php

namespace app\api\controller;

use app\extra\zgysbase\JWT;
use think\Controller;

class Token extends Controller
{

    public static   $key  = "";
    /**
     * 创建token
     * @param $account 账户
     * @param $type 账户类型
     * @return \think\response\Json
     */
    public function create($account,$type)
    {
        $access_token['access_token']    =  TokenUtil::getToken($account,$type);
        $req['success'] = true;
        $req['msg'] = "创建成功";
        $req['code'] = 0;
        $req['data'] = $access_token;

        // 数据库记录token信息
        $ctime          = time();
        $expire_in      = 3600*24*31;
        switch($type)
        {
            case "mobile":
                $type = 1;
                break;
            case "email" :
                $type = 2;
                break;
            case "admin":
                $type = 3;
                break;
            default:
                $type = 1;
                break;
        }

        $tokenModel     = new \app\api\model\Token();
        $tokenModel->addToken($account,$type,$access_token['access_token'],$ctime,$expire_in);

        return json($req);
    }

    /**
     * 根据token进行解码
     * @param $token
     * @return \think\response\Json
     */
    public function decode($token)
    {

        $token = cookie('access_token');
        try{
            $decoded = JWT::decode($token, Token::$key, array('HS256'));
            $req['success'] = true;
            $req['msg'] = "ok";
            $req['code'] = 0;
            $req['data'] = $decoded;
        }
        catch(\Exception $e){
            $req['success'] = false;
            $req['msg'] = "error";
            $req['code'] = 500;
            $req['data'] = $e->getMessage();
        }
        return json($req);
    }

    public function mobile_cookie()
    {
        $key = self::$key;
        $token = array(
            "account" => "18310742004",
            'type'    => "mobile",
            "ctime" => time(),
        );
        $access_token    =  JWT::encode($token,$key);
        cookie("access_token",$access_token);
    }

    // 测试阶段写入COOKIE 利于测试
    public function cookie()
    {
        $key = self::$key;
        $token = array(
            "account" => "15003945225",
            'type'    => "admin",
            "ctime" => time(),
        );
        $access_token    =  JWT::encode($token,$key);
        cookie("access_token",$access_token);
        print_r($_COOKIE);
    }
}
