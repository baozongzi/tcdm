<?php

namespace app\admin\model;

use think\Model;

class Ad extends Model
{
    // 表名
    protected $name = 'ad';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = true;

    // 定义广告类型
    protected $adType = [0 => 'banner广告', 1 => '插屏广告'];
    // 定义广告页面
    protected $adPage = [0 => '试题页面', 1 => '活动页面', 2 => '培训页面', 3 => '启动页面'];
    
    // 追加属性
    protected $append = [];


    /**
     * 获取广告类型列表
     * @return array
     */
    public function getTypeList()
    {
        return $this->adType;
    }

    /**
     * 获取页面类型列表
     * @return array
     */
    public function getPageList()
    {
        return $this->adPage;
    }
    

}
