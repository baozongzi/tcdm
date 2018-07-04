<?php

namespace app\api\model;

use think\Model;

class PapersQuestions extends Model
{
    // 表名
    protected $name = 'papers_questions';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
    ];


    public function papers()
    {
        return $this->hasMany('Papers', 'id', 'pid');
    }
}
