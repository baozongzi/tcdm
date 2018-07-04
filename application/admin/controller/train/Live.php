<?php

namespace app\admin\controller\train;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Live extends Backend
{
    
    /**
     * TrainLive模型对象
     */
    protected $model = null;

    /**
     * 搜索字段
     */
    protected $searchFields = 'id,title,date,time,url,tags';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('TrainLive');

    }
}
