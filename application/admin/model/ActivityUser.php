<?php

namespace app\admin\model;

use think\Model;
use think\Db;

class ActivityUser extends Model
{
    // 表名
    protected $name = 'activity_user';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'add_time_text'
    ];
    

    



    public function getAddTimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['add_time'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setAddTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    /**
     * 获取活动报名列表
     * @param null $ids 活动id
     */
    public function getActivityUser($uid = null)
    {
        return model('User')->field('id,username,mobile')->where('id',$uid)->find();
    }


}
