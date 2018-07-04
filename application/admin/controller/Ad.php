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
class Ad extends Backend
{
    
    /**
     * Ad模型对象
     */
    protected $model = null;

    /**
     * 搜索字段
     */
    protected $searchFields = 'id,type,page,content,url';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Ad');
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("pageList", $this->model->getPageList());

    }


    /**
     * 获取广告类型列表
     */
    public function getTypeListAjax()
    {
        $list = $this->model->getTypeList();
        $searchlist = [];
        foreach ($list as $key => $value)
        {
            $searchlist[] = ['id' => $key, 'name' => $value];
        }
        $data = ['searchlist' => $searchlist];
        $this->success('', null, $data);
    }

    /**
     * 获取页面类型列表
     */
    public function getPageListAjax()
    {
        $list = $this->model->getPageList();
        $searchlist = [];
        foreach ($list as $key => $value)
        {
            $searchlist[] = ['id' => $key, 'name' => $value];
        }
        $data = ['searchlist' => $searchlist];
        $this->success('', null, $data);
    }

}
