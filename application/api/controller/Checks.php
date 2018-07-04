<?php
/**
 * Created by PhpStorm.
 * User: wangcailin
 * Date: 2017/10/30
 * Time: 下午4:42
 */
namespace app\api\controller;

use app\common\controller\Api;
use think\Validate;
use app\common\library\Alidayu;

class Checks extends Api
{

    public function checks(){
        $row['mobile'] = input('mobile');
        $row['password'] = input('password');

        // $aString = '$b = '.var_export($row, true).';'; 
        // echo "<pre>";
        // fopen(APP_PATH.'/api/controller/log.txt','w');
        // file_put_contents(APP_PATH.'/api/controller/log.txt',$aString);
        // var_dump($row);
        // die;
        $str = base64_encode(json_encode($row));
        echo $str;
        die;
    }

    public function access_token(){
        $row = input('row/a');
        $arr = array(
                'mobile'    =>  $row['mobile'],
            );
        $base64 = base64_encode(json_encode($arr));
        $key = 'mcykj-forever';
        $hmac = hash_hmac('sha256', $base64, $key, $as_binary=false);
        echo "<pre>";
        print_r($hmac);
        die;
    }
}