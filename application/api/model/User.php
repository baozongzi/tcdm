<?php
/**
 * Created by PhpStorm.
 * User: wangcailin
 * Date: 2017/10/30
 * Time: 下午4:42
 */
namespace app\api\model;

use think\Model;
class User extends Model
{
    // 表名
    protected $name = 'user';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;

    // 追加属性
    protected $append = [

    ];

    /**
     * 登录验证
     */
    public function loginCheck($data)
    {
        if (!$res = $this->where('mobile', $data['mobile'])->find()){
            return 0; //手机号不存在
        }

        $string = $res->string;

        if (!$res = $this->where('password', md5($data['password'].$string))->field('id,username,mobile,nickname,createtime')->find()){
            return 1; //密码错误
        }else{
            $res = $this->where('password', md5($data['password'].$string))->field('id,username,mobile,nickname,createtime')->find()->getData();
            return $res; //登录成功
        }
    }

    /**
     * 用户忘记密码处理
     */
    public function forgetPasswordCheck($mobile)
    {
        if ($res = $this->where('mobile', $mobile)->find()){
            return $res;
        }
        return false;
    }

    // 查找用户信息
    public function finds($mobile){
        if ($user = $this->where('mobile',$mobile)->find()){
            return $user;
        }
        return false;
    }

}