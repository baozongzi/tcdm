<?php

namespace app\api\model;

use think\Model;

class Questions extends Model
{
    // 表名
    protected $name = 'questions';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [];

    // 定义题目类型
    protected $questionsType = [0 => '单选题', 1 => '多选题', 2 => '判断题', 3 => '主观题'];

    //字段类型转换
    protected $type = ['options' => 'json', 'images' => 'json'];
    

    public function getChaptersQuestionList($ids = null)
    {
        $where['category'] = ['like', '%,'.$ids.',%'];
        $where['status']   = '1';
        return $this->where($where)->select();
    }
}
