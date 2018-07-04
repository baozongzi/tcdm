<?php

namespace app\admin\controller;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Feedback extends Backend
{
    
    /**
     * Feedback模型对象
     */
    protected $model = null;

    protected $searchFields = 'id,title,content';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Feedback');

    }
   
    /**
     * 详情
     */
    public function detail($ids)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign("row", $row->toArray());
        return $this->view->fetch();
    }


}
