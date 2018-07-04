<?php

namespace app\admin\model;

use think\Model;

class TrainVideo extends Model
{
    // 表名
    protected $name = 'train_video';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [];

    public function getFlagList()
    {
        return ['news' => __('News'), 'top' => __('Top'), 'sales' => __('Sales')];
    }
}
