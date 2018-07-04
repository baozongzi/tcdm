<?php

namespace app\admin\model;

use think\Model;

class Push extends Model
{
    // 表名
    protected $name = 'push';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'time';
    protected $updateTime = 'time';
    
    // 追加属性
    protected $append = [];

    public function getTimeAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $value);
    }

}
