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
        // $this->view->assign("typeList", $this->model->getTypeList());
        // $this->view->assign("pageList", $this->model->getPageList());

    }

        /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order,$offset, $limit) = $this->buildparams();
            // $status['status'] = 1; // 非回收站
            $total = $this->model
                // ->where($status)
                ->order($sort,$order)
                ->count();
            $list = $this->model
                // ->where($status)
                ->order($sort,$order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $params['updatetime'] = time();
            if ($params)
            {
                $this->model->save($params);
                $this->success();
            }
            $this->error();
        }
        $this->assign('row',$row);
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids)->getData();

        if (!$row)
            $this->error(__('No Results were found'));
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds))
        {
            if (!in_array($row[$this->dataLimitField], $adminIds))
            {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            $params['updatetime'] = time();
            if ($params)
            {
                $result = $this->model->save($params,['id' => $ids]);
                if ($result !== false)
                {
                    $this->success();
                }
                $this->error($row->getError());
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->assign('row',$row);
        return $this->view->fetch();
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
