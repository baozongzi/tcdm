<?php
/**
 * Created by PhpStorm.
 * User: jokechat
 * Date: 2017/9/19
 * Time: 16:42
 */
namespace mcykj;

use app\api\model\Employee;
use app\service\model\AppPushConfig;
use app\service\model\AppPushLog;
use app\service\model\AppPushUser;
use function Couchbase\defaultDecoder;
use JPush\Client;
use think\Log;

/**
 * 自定义类库文件,完成扩展操作
 * Class PushNotice
 * @package mcykj
 */
class PushNotice
{

    /**
     * 发送推送消息
     * @param array $alias 用户别名数组
     * @param string $type 推送类型 1 客户端;2,维修端
     * @param $name 模块名称
     * @param string $title 标题
     * @param string $desc 描述
     * @param string $thumb 缩略图
     * @param $schema_prefix 协议前缀
     * @param $scheme_suffix 协议后缀
     * @param string $app_name 应用名称
     * @return array
     */
    public function sendPush($alias,$type,$name,$title,$desc,$thumb,$schema_prefix,$scheme_suffix,$app_name)
    {
        $appPushConfig  = new AppPushConfig();
        $userModel      = new AppPushUser();
        $logModel       = new AppPushLog();

        // 获取极光推动 应用配置
        if('wuye_client' == $app_name){
            $clicne_config  = $appPushConfig->getConfig("wuye_client"); // 客户端配置
            $client         = new Client($clicne_config->app_key,$clicne_config->master_secret);
        }else{
            $clicne_config  = $appPushConfig->getConfig("wuye_weixiu"); // 客户端配置
            $client         = new Client($clicne_config->app_key,$clicne_config->master_secret);
        }

        $schema             = $schema_prefix.$scheme_suffix;
        // 推送消息
        $notification      =[
            'sound'             => 'sound.caf',
            'badge'             => "+1",
            'mutable-content'   => true,
            'extras'            => [
                'schema'        => $schema,
                'imageAbsoluteString'   => $thumb,
                'title'         => $title,
                'desc'          => $desc
            ]
        ];

        // 记录推送记录
        foreach ($alias as $k => $v){
            $logModel->addLog($v,$type,$name,$title,$desc,$thumb,$schema_prefix,$scheme_suffix);
        }

        $result         = $client->push()
                                ->setPlatform("all")
                                ->addAlias($alias)
                                ->setOptions(time(),3600,null,false)
                                ->iosNotification($title, $notification)
                                ->androidNotification($title, $notification)
                                ->send();

        Log::record("极光推送消息");
        Log::record($result);

        return $result;
    }

    /**
     * 给所有员工发送push消息
     * @param $orderid
     * @param $username
     * @param string $action
     * @param array $alias
     * @return array
     */
    public function noticeToEmployee($orderid,$username,$action = "default",$alias = [])
    {
        $schema_prefix  = "";
        $scheme_suffix  = "";
        switch ($action)
        {
            case "proprietor_repair":
                // 业主提交报修
                $title  = "新的维修工单";
                $desc   = "用户{username}提交了新的维修工单（工单号：{orderid}），请尽快处理。";
                $desc   = str_replace("{username}",$username,$desc);
                $desc   = str_replace("{orderid}",$orderid,$desc);

                $schema_prefix  = "wuyexiu://";
                $scheme_suffix  = [
                    'action'    => 'repair',
                    'repair_id'   => $orderid
                ];
                break;

            case "employee_apply_repair":
                // 维修员申请重新派修
                $title  = "重新拍单申请";
                $desc   = "员工{username}提交了工单重新分派的申请（工单号：{orderid}），请到后台进行操作。";
                $desc   = str_replace("{username}",$username,$desc);
                $desc   = str_replace("{orderid}",$orderid,$desc);
                $schema_prefix  = "wuyexiu://";
                $scheme_suffix  = [
                    'action'    => 'repair',
                    'repair_id'   => $orderid
                ];
                break;

            case "admin_dispatch_reair_order":
                $title  = "维修指派";
                $desc   = "管理员给您指派了报修订单,（工单号：{orderid}），请尽快处理。";
                $desc   = str_replace("{orderid}",$orderid,$desc);
                $schema_prefix  = "wuyexiu://";
                $scheme_suffix  = [
                    'action'    => 'repair',
                    'repair_id'   => $orderid
                ];
                break;

            case "goods_order_pay_success":
                // 商城支付成功待发货
                $title  = "新的订单";
                $desc   = "用户{username}下单了（订单号：{orderid}），尽快发货吧!";
                $desc   = str_replace("{username}",$username,$desc);
                $desc   = str_replace("{orderid}",$orderid,$desc);
                $schema_prefix  = "wuyexiu://";
                $scheme_suffix  = [
                    'action'    => 'goods_order',
                    'orderid'   => $orderid
                ];
                break;

            case "house_payment_success":
                // 物业缴费成功
                $title  = "缴费订单";
                $desc   = "用户{username}提交了新的缴费订单（缴费订单号：{orderid}），请尽快到后台进行处理!";
                $desc   = str_replace("{username}",$username,$desc);
                $desc   = str_replace("{orderid}",$orderid,$desc);
                $scheme_suffix  = [
                    'action'    => 'house_payment_order',
                    'orderid'   => $orderid
                ];
                break;

            default:
                break;
        }

        if(empty($alias)){
            $userModel  = new AppPushUser();
            $alias      = $userModel->getUserList(2,'alias');
            $alias      = json_decode(json_encode($alias,true),true);
            $alias      = array_map('array_shift',$alias);

            // 过滤排除请假员工
            $emploModel     = new Employee();
            $where['jobnumber'] = ['in',implode($alias,",")];
            $where['is_rest']   = 0;
            $jobnumbers         = $emploModel->where($where)->field("jobnumber")->select();
            $alias      = array_map('array_shift',collection($jobnumbers)->toArray());
        }
        $result         = $this->sendPush($alias,2,'notice',$title,$desc,'',$schema_prefix,http_build_query($scheme_suffix),'wuyexiu');
        return $result;

    }

    /**
     * 发送push到指定客户端用户
     * @param $orderid
     * @param $alias
     * @param $action
     * @return array
     */
    public function noticeToProprietor($orderid,$alias,$action)
    {
        switch ($action)
        {
            case "repair_start" :
                // 报修订单开始处理
                $name   = "repair";
                $title  = "维修订单开始";
                $desc   = "维修员正在赶赴现场进行处理，请耐心等待";
                $schema_prefix  = "wuyeclient://";
                $scheme_suffix  = [
                    'action'    => 'repair',
                    'orderid'   => $orderid
                ];
                break;

            case "repair_finshed":
                // 报修订单开始处理
                $name   = "repair";
                $title  = "维修订单完成";
                $desc   = "维修员确认完成维修工作，赶快去给个好评吧^^";
                $schema_prefix  = "wuyeclient://";
                $scheme_suffix  = [
                    'action'    => 'repair',
                    'orderid'   => $orderid
                ];
                break;

            case "goods_delivery":
                // 商城订单发货
                $name   = "goods_order";
                $title  = "订单发货";
                $desc   = "您的订单 $orderid 已经发货，请耐心等待快递小哥送货上门^^";
                $schema_prefix  = "wuyeclient://";
                $scheme_suffix  = [
                    'action'    => 'goods_order',
                    'orderid'   => $orderid
                ];
                break;

            case "house_payment_finshed":
                // 物业缴费完成
                $name   = "house_payment_order";
                $title  = "恭喜您缴费成功!";
                $desc   = "物业缴费成功，点击查看详情。";
                $schema_prefix  = "wuyeclient://";
                $scheme_suffix  = [
                    'action'    => 'house_payment_order',
                    'orderid'   => $orderid
                ];
                break;
            default:
                break;
        }
        $result         = $this->sendPush($alias,1,$name,$title,$desc,'',$schema_prefix,http_build_query($scheme_suffix),'wuye_client');
        return $result;
    }
}