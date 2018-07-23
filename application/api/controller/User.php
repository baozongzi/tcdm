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
use app\common\library\Rest as Rest;

class User extends Api
{

    /**
     * Teacher模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        session_start();
        parent::_initialize();
        $this->model = model('User');

        $token = cookie('access_token');
        $this->row = input('row');
        $this->row = base64_decode($this->row);
        $this->row = json_decode($this->row);
        if(isset($this->row->urlParams)){
            $this->row = $this->row->urlParams;
        }
        if(isset($this->row->userid) && $this->row->userid !== '-1'){
            $this->userid = $this->row->userid;
            $this->rule($token,$this->userid);
        }
        $this->mobile = $this->row->mobile;

        $string = 'mcy-zgys';
    }

    /**
     * 会员登录接口
     */
    public function login()
    {   
        $mobile    = $this->row->mobile;
        $password  = $this->row->password;
        $unique    = $this->row->unique;
        $res = $this->model->loginCheck(array('mobile'=> $mobile,'password'=> $password));
        if ($res == 0){
            $res = $this->json_echo('0','手机号不存在😏',array());
            return $res;
        }elseif ($res == 1){
            $res = $this->json_echo('1','密码错误😏',array());
            return $res;
        }else{
            $message = array(
                'mobile'    =>  $mobile,
                'password'  =>  $password,
                'unique'    =>  $unique,
            );
            $access_token = $this->create_token($message);
            $update = array(
                'access_token'  => $access_token,
            );
            if ($this->model->where("mobile = '".$mobile."'")->update($update)){
                $res = $this->json_echo('1','欢迎回来😏',$access_token);
                return $res;
            }

            $res = $this->json_echo('1','登录成功😏',$access_token);
            return $res;
        }
    }

    /**
     * 会员注册接口
     */
    public function register()
    {
        $mobile    = $this->mobile;
        $code  = $this->row->code;
        $password  = $this->row->password;
        if($mobile !== $_SESSION['mobile']){
            $res = $this->json_echo('0','手机号不正确😏',array());
            return $res;
        }
        if($password !== $this->row->repassword){
            $res = $this->json_echo('0','两次密码不一致😏',array());
            return $res;
        }
        if(intval($code) !== $_SESSION['code']){
            $res = $this->json_echo('0','验证码不正确😏',array());
            return $res;
        }
        $string = 'mcy-zgys';
        $rule = array(
            'mobile'  => 'require|length:11',
        );
        $validate = new Validate($rule);
        $result = $validate->check(array('mobile'  => $mobile));
        if (!$result)
        {
            $res = $this->json_echo('0','请输入11位手机号😏',array());
            return $res;
        }
        $data = array(
            'mobile'  => $mobile,
        );
        $res = $this->model->where($data)->count();
        if ($res){
            $res = $this->json_echo('0','手机号已存在😏',array());
            return $res;
        }
        $data['password'] = $password;
        $data['unique'] = $this->row->unique;
        $access_token = $this->create_token($data);

        $save = array(
            'mobile'        => $mobile,
            'username'      => $mobile,
            'password'      => md5($password.$string),
            'string'        => $string,
            'access_token'  => $access_token
        );
        if ($this->model->save($save))
        {
            $message = array(
                'access_token'  => $access_token
            );
            $res = $this->json_echo('1','注册成功😏',$message);
            return $res;
        }

        $res = $this->json_echo('0','注册失败😏',array());
        return $res;
    }

    /**
     * 修改密码
     * @return \think\response\Json
     */
    public function passwordCheck()
    {

        $string = 'mcy-zgys';
        $row = input('row/a');
        $mobile    = $row['mobile'];
        $password  = $row['password'];
        // $oldpassword  = $row['oldpassword'];

        $user = model('User')->finds($mobile);
        $data = array(
            'mobile'    => $mobile,
            'password'  => $password
        );
        $arr = array(
            'mobile'    => $mobile,
        );

        // if(md5($oldpassword.$string) !== $user['password']){
        //     return api_json('1', '原始密码错误', $arr);
        // }
        $res = $this->model->save([
                'password' => md5($password.$string),
                'string'   => $string
        ], ['mobile'   => $mobile]);
        if ($res){
            return api_json('0', '修改成功', $arr);
        }
        return api_json('1', '修改失败', $arr);
    }

    // 修改用户名
    public function nicknamecheck(){

    }

    public function sendmes(){
        $mobile = $this->row->mobile;
        $code = rand(111111,999999);
        $_SESSION['code'] = $code;
        $_SESSION['mobile'] = $mobile;
        if(!isset($_SESSION['sendmestime'])){
            $_SESSION['sendmestime'] = time();
        }
        if(time() > $_SESSION['sendmestime'] + 60){
            $_SESSION['sendmestime'] = time();
        }

        $res = $this->json_echo('0','发送成功😏',$_SESSION);
        return $res;

        $datas = array("".$code."",'5');
        $tempId = '1';
        $num = $this->messsend($mobile,$datas,$tempId);
        if($num == '0'){
            $res = $this->json_echo('0','发送失败😏',array());
            return $res;
        }else{
            $res = $this->json_echo('1','发送成功😏',array());
            return $res;
        }
    }

    
    function messsend($to,$datas,$tempId){
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        $accountSid= '8a216da864a7c9e30164ac06976f0253';
        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        $accountToken= 'ea143f551d2d49b8bc954bb42dc7aa10';
        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        $appId='8a216da864a7c9e30164ac0697c40259';
        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        $serverIP='app.cloopen.com';
        //请求端口，生产环境和沙盒环境一致
        $serverPort='8883';
        //REST版本号，在官网文档REST介绍中获得。
        $softVersion='2013-12-26';
        // 初始化REST SDK
        // global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
        // print_r($serverIP);die();
        $rest = new Rest($serverIP,$serverPort,$softVersion);
        // $rest = require SITE_URL'/Home/Logic/Rest.class.php';
        // $rest = $restLogic;
        $rest->setAccount($accountSid,$accountToken);
        $rest->setAppId($appId);
        // 发送模板短信
        // echo "Sending TemplateSMS to $to <br/>";
        $result = $rest->sendTemplateSMS($to,$datas,$tempId);
        if($result == NULL ) {
             echo "result error!";
             exit;
             // break;
        }
        if($result->statusCode!=0) {
             // echo "error code :" . $result->statusCode . "<br>";
             // echo "error msg :" . $result->statusMsg . "<br>";
            $status = '0';
            return $status;
             //TODO 添加错误处理逻辑
        }else{
             // echo "Sendind TemplateSMS success!<br/>";
             // // 获取返回信息
             // $smsmessage = $result->TemplateSMS;
             // echo "dateCreated:".$smsmessage->dateCreated."<br/>";
             // echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
            
            $status = '1';
            return $status;
             //TODO 添加成功处理逻辑
        }
         //TODO 添加成功处理逻辑
    }

    

}
