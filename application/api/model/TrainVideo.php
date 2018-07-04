<?php

namespace app\api\model;

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
    protected $append = [
    ];


    /**
     * 获取有标签视频课程接口
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getVideoWeigh()
    {
        return collection($this->where('flag', 'NEQ', '')->order('weigh', 'DESC')->select())->toArray();
    }
    

}
