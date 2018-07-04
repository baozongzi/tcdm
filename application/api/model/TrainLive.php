<?php

namespace app\api\model;

use think\Model;

class TrainLive extends Model
{
    // 表名
    protected $name = 'train_live';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
    ];

    /**
     * 获取制定直播视频接口5条数据
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getLiveWeigh()
    {
        return collection($this->order('weigh', 'DESC')->limit(0,5)->select())->toArray();
    }
    

}
