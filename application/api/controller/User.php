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

class User extends Api
{

    /**
     * Teacher模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');

        // print_r($_SERVER);
        // 加密操作
        
        $this->row = input('row');
        $this->row = base64_decode($this->row);
        $this->row = json_decode($this->row);
        $token = cookie('access_token');
        $string = 'mcy-zgys';
    }

    /**
     * 会员登录接口
     */
    public function login()
    {   
        // $arr = array(
        //         'mobile'    =>  '13800138001',
        //         'password'      =>  '123456',
        //         'unique'    =>  '123456',
        //     );
        // $base64 = base64_encode(json_encode($arr));
        // echo "<pre>";
        // print_r($base64);
        // die;
        $mobile    = $this->row->mobile;
        $password  = $this->row->password;
        $unique    = $this->row->unique;
        $res = $this->model->loginCheck(array('mobile'=> $mobile,'password'=> $password));
        $message = array(
            'mobile'    =>  $mobile,
            'password'  =>  $password,
            'unique'    =>  $unique,
        );
        if ($res == 0){
            return api_json('1', '手机号不存在', $message);
        }elseif ($res == 1){
            return api_json('1', '密码错误', $message);
        }else{
            $access_token = $this->create_token($message);
            $update = array(
                'mobile'        => $mobile,
                'unique'    => $unique,
                'access_token'  => $access_token,
            );
            if ($this->model->where("mobile = '".$mobile."'")->update($update)){
                return api_json('0', '欢迎回来', $update);
            }
            return api_json('0', '登录成功', $update);
        }
    }

    /**
     * 会员注册接口
     */
    public function register()
    {

        $row = input('row/a');
        $mobile    = $row['mobile'];
        $password  = $row['password'];

        $string = 'mcy-zgys';
        $rule = array(
            'mobile'  => 'require|length:11',
        );
        $data = array(
            'mobile'  => $mobile,
        );
        $validate = new Validate($rule);
        $result = $validate->check(array('mobile'  => $mobile));
        if (!$result)
        {
            return api_json('1', '请输入11位手机号', array());
        }
        $res = $this->model->where($data)->count();
        if ($res){
            return api_json('1', '手机号已存在', $data);
        }

        $data['unique'] = $row['unique'];
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
                'mobile'        => $mobile,
                'username'      => $mobile,
                'access_token'  => $access_token
            );
            return api_json('0', '注册成功', $message);
        }

        return api_json('1', '注册失败', array());
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

    /**
     * 发送短信验证码接口
     */
    public function sendSms()
    {
        $type   = input('type');
        $mobile = input('mobile');
        $code = create_code();
        $alidayu = new Alidayu;
        $res = $this->model->forgetPasswordCheck($mobile);

        switch ($type){
            case '1': // 找回密码
                if ($res){
                    if ($alidayu::send($mobile, $code, 'SMS_109690327')){
                        return api_json('1', '验证码发送失败', array());
                    }
                    return api_json('0', '验证码发送成功', $code);
                }
                return api_json('1', '手机号不存在', array());
                break;
            case '2': // 注册
                if (!$res){
                    if ($alidayu::send($mobile, $code, 'SMS_109690329')){
                        return api_json('1', '验证码发送失败', array());
                    }
                    return api_json('0', '验证码发送成功', $code);
                }
                return api_json('1', '手机号已存在', array());
                break;
            default:
                return false;
        }

    }

    

}
