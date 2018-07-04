<?php

namespace app\admin\model;

use think\Model;

class PapersRule extends Model
{
    // 表名
    protected $name = 'papers_rule';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'time'
    ];
    

    



    public function getTimeAttr($value, $data)
    {
        return $value/60;
    }

    protected function setTimeAttr($value)
    {
        return $value*60;
    }


}
