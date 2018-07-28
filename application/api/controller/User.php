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
use think\Db;

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
        if(isset($this->row->mobile)){
            $this->mobile = $this->row->mobile;
        }
        $this->page   = isset($this->row->page) ? $this->row->page : 1;
        $this->offset = ($this->page - 1) * 2;
        $this->limit  = $this->page * 2;
        $this->string = 'mcy-zgys';
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
            $user = Db::table('fa_user')->field('id,access_token')->where("mobile = '".$mobile."'")->find();

            if ($this->model->where("mobile = '".$mobile."'")->update($update)){
                $res = $this->json_echo('1','欢迎回来😏',$user);
                return $res;
            }

            $res = $this->json_echo('1','登录成功😏',$user);
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
        $this->string = 'mcy-zgys';
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
            'password'      => md5($password.$this->string),
            'string'        => $this->string,
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
    // 忘记密码
    public function retrieve(){
        $mobile = $this->row->mobile;
        $code = $this->row->code;
        $user = Db::table('fa_user')->where("mobile = '".$mobile."'")->field('id')->find();
        if(empty($user) || $mobile !== $_SESSION['mobile']){
            $res = $this->json_echo('0','手机号不正确😏',array());
            return $res;
        }
        if(intval($code) !== $_SESSION['code']){
            $res = $this->json_echo('0','验证码不正确😏',array());
            return $res;
        }
        return $this->json_echo('1','校验成功😏',array());
    }
    // 重置密码
    public function resetpass(){
        if($this->row->password !== $this->row->repassword){
            $res = $this->json_echo('0','两次密码不一致😏',array());
            return $res;
        }
        $update['password'] = md5($password.$this->string);
        $res = $this->model->where("mobile = '".$_SESSION['mobile']."'")->update($update);
        if($res){
            return $this->json_echo('1','重置密码成功😏',array());
        }
    }

    /**
     * 修改密码
     * @return \think\response\Json
     */
    public function passwordCheck()
    {

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
                'password' => md5($password.$this->string),
                'string'   => $this->string
        ], ['mobile'   => $mobile]);
        if ($res){
            return api_json('0', '修改成功', $arr);
        }
        return api_json('1', '修改失败', $arr);
    }

    // 艺人基本资料提交
    public function artistmes(){
        $data['userid'] = $this->row->userid;
        $data['normal_name'] = $this->row->normal_name;
        $data['sex'] = $this->row->sex;
        $data['birthday'] = $this->row->birthday;
        $data['height'] = $this->row->height;
        $data['area'] = $this->row->area;
        $data['constellation'] = $this->row->constellation;
        $data['intro'] = $this->row->intro;
        $basics = Db::table('fa_u_examine_basics')->where('userid = '.$data['userid'])->field('is_ok')->find();
        if($basics['is_ok'] == '2'){
            return $this->json_echo('1', '审核中...', array());
        }
        $res = Db::table('fa_u_examine_basics')->insert($data);
        if($res){
            return $this->json_echo('1', '申请成功,等待审核吧!', array());
        }
    }
    // 艺人照片资料提交
    public function artistimgs(){
        // $data = file_get_contents('php://input');
        $imgs = request()->file('');
        $imgone = $imgs['imgone'];
        echo "<pre>";
        print_r($imgone);
        echo "string";
        die;
        $file = explode("&head_pic=", $data);
        $df = $file['1'];
        // var_dump($file);
        $path = 'Public/upload/head_pic';
        $this->creatfile($path);
        $name=time();
        $file = $path.'/' . $name . '.png';

        file_put_contents($file, $df);
    }

    // 修改用户名
    public function nicknamecheck(){

    }

    public function sendmes(){
        $mobile = $this->row->mobile;
        $user = Db::table('fa_user')->where("mobile = '".$mobile."'")->field('userid')->find();
        if(empty($user)){
            $res = $this->json_echo('0','用户不存在😏',array());
            return $res;
        }
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

    // 帮助与反馈
    public function help(){
        $data['userid'] = $this->row->userid;
        $data['help'] = $this->row->help;
        $data['type'] = $this->row->type;
        $data['mobile'] = $this->row->mobile;
        $res = Db::table('fa_help')->insert($data);
        if($res){
            return $this->json_echo('1','提交成功😏',array());
        }
    }

    // 绑定手机号
    public function relationtel(){
        $userid = $this->row->userid;
        $data['mobile'] = $this->row->mobile;
        $code = $this->row->code;

        if(intval($code) !== $_SESSION['code']){
            return $this->json_echo('0','验证码不正确😏',array());
        }
        $res = Db::table('fa_user')->where('id = '.$userid)->update($data);
        if($res){
            return $this->json_echo('1','绑定成功😏',array());
        }else{
            return $this->json_echo('0','用户信息不正确😏',array());
        }
    }

    // 个人中心
    public function usercenter(){
        $userid = $this->row->userid;
        $user = Db::table('fa_user')->where('id = '.$userid)->field('id,nickname,normal_name,money,diamond,head,vip')->find();
        $user['head'] = $this->website.$user['head'];
        $user['history'] = Db::table('fa_history')->where('userid = '.$this->userid)->limit(10)->select();
        return $this->json_echo('1','获取成功😏',$user);
    }

    // 观看历史
    public function historys(){
        $history = Db::table('fa_history')->where('userid = '.$this->userid)->limit($this->offset, $this->limit)->select();
        return $this->json_echo('1','获取成功😏',$history);
    }

    // 账户与安全
    public function safe(){
        $type = $this->row->type;
        if($type == '0'){
            $user = Db::table('fa_user')->field('head,nickname,sex,birthday,mobile,intro')->where('id = '.$this->row->userid)->find();
            $user['head'] = $this->website.$user['head'];
        }else if($type == '1'){
            $user = Db::table('fa_user')->alias('u')
                                        ->join('fa_u_examine_basics ueb','ueb.userid = u.id')
                                        ->field('head,nickname,ueb.sex,ueb.birthday,mobile,ueb.intro,height,constellation,area')
                                        ->where('u.id = '.$this->row->userid)
                                        ->find();
            $user['head'] = $this->website.$user['head'];
        }
        return $this->json_echo('1','获取成功😏',$user);
    }

    // 我的关注
    public function follow(){
        $userid = $this->row->userid;
        $follow = Db::table('fa_fans')->alias('f')
                                      ->join('fa_user u','f.artistid = u.id')
                                      ->field('f.artistid,head,normal_name')
                                      ->where('userid = '.$userid)
                                      ->select();
        foreach ($follow as $f => $fw) {
            $follow[$f]['head'] = $this->website.$follow[$f]['head'];
            $follow[$f]['num'] = Db::table('fa_fans')->where('artistid = '.$follow[$f]['artistid'])->count();
        }
        return $this->json_echo('1','获取成功😏',$follow);
    }
    // 注册协议
    public function agreement(){
        $message = Db::table('fa_message')->where('type = 2')->find();
        return $this->json_echo('1','获取成功😏',$message);
    }
    // 我的收藏
    public function collect(){
        $collect = Db::table('fa_collection')->field('id,vid,tables,title,inputtime,cid')->where('userid = '.$this->row->userid)->select();
        return $this->json_echo('1','获取成功😏',$collect);
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
