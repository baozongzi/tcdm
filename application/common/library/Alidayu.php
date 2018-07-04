<?php

namespace app\common\library;

/**
 * Created by PhpStorm.
 * User: wangcailin
 * Date: 2017/11/13
 * Time: 下午3:43
 */

use alidayu\Sms;

class Alidayu
{
    /**
     * 发送验证码
     * @param $mobile    手机号
     * @param $code      验证码
     * @param $signName  短信模板
     * @return bool
     */
    public static function send($mobile, $code, $signName)
    {
        $alidayu = new Sms;
        $response = $alidayu::sendSms(
            "中国影视", // 短信签名
            $signName, // 短信模板编号
            $mobile, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=> $code,
            )
        );
        if ($response->Code != '0'){
            return false;
        }
        return true;
    }
}
