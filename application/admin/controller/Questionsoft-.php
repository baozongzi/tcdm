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
class Questionsoft extends Backend
{
    
    /**
     * Questions模型对象
     */
    protected $model = null;

    protected $searchFields = 'id,title,options,answer,analysis';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Questions');
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("imageList", $this->model->getImageList());

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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $status = ['status' => 0];
            $total = $this->model
                ->where($where)
                ->where($status)
                ->order($sort, $order)
                ->count();
            $list = $this->model
                ->where($where)
                ->where($status)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 还原
     */
    public function multi($ids = "")
    {
        $ids = $ids ? $ids : $this->request->param("ids");
        if ($ids)
        {
            if ($this->request->has('params'))
            {
                parse_str($this->request->post("params"), $values);
                $values = array_intersect_key($values, array_flip(is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields)));
                if ($values)
                {
                    $adminIds = $this->getDataLimitAdminIds();
                    if (is_array($adminIds))
                    {
                        $this->model->where($this->dataLimitField, 'in', $adminIds);
                    }
                    $count = $this->model->where($this->model->getPk(), 'in', $ids)->update($values);
                    if ($count)
                    {
                        $this->success();
                    }
                }
                else
                {
                    $this->error(__('You have no permission'));
                }
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

//    public function softReduction()
//    {
//        $ids = input('id');
//        if ($ids)
//        {
//            $where['id'] = ['in', $ids];
//            $count = $this->model->where($where)->update(['status' => 1]);
//            if ($count)
//            {
//                $this->success();
//            }
//        }
//        $this->error(__('Parameter %s can not be empty', 'ids'));
//    }
}
