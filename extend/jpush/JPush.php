<?php
/**
 * Created by PhpStorm.
 * User: wangcailin
 * Date: 2017/12/8
 * Time: 上午10:10
 */

namespace jpush;

use JPush\Client;
use app\api\model\Config;


class JPush
{

    /**
     * 发送推送消息
     * @param $title  标题
     * @param $desc   描述
     * @param $url  跳转链接
     * @return array
     */
    public function sendPush($title, $desc, $url)
    {
        $config      = new Config();
        $jpushKey    = $config->where('name', 'jpushkey')->value('value');
        $jpushSecret = $config->where('name', 'jpushsecret')->value('value');
        $jpushSchema = $config->where('name', 'jpushschema')->value('value');

        $client = new Client($jpushKey, $jpushSecret);

        $notification      =[
            'extras'            => [
                'schema'   => $jpushSchema . $url,
                'title'    => $title,
                'desc'     => $desc
            ]
        ];

        $result = $client->push()
            ->setPlatform(array('ios', 'android'))
            ->addAllAudience()
            ->setOptions(time(),3600,null,false)
            ->iosNotification($title, $notification)
            ->androidNotification($title, $notification)
            ->send();

        return $result;
    }
}