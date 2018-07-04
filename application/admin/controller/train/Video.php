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
class Video extends Backend
{
    
    /**
     * TrainVideo模型对象
     */
    protected $model = null;

    /**
     * 搜索字段
     */
    protected $searchFields = 'id,title,flag,tags,money,url';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('TrainVideo');
        $this->view->assign("flagList", $this->model->getFlagList());
    }
}
