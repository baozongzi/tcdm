<?php

namespace app\admin\model;

use think\Model;

class Activity extends Model
{
    // 表名
    protected $name = 'activity';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 定义广告类型
    protected $activityType = [0 => '线上', 1 => '线下'];

    // 追加属性
    protected $append = [];
    
    protected function setTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    /**
     * 读取活动类型
     * @return array
     */
    public function getTypeList()
    {
        return $this->activityType;
    }




}
