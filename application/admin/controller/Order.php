<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Controller;
use fast\Tree;
use think\Cache;
use think\Request;
use think\Db;

/**
 * @icon fa fa-circle-o
 */
class Order extends Backend
{
    
    /**
     * Videos模型对象
     */
    protected $model = null;

    protected $searchFields = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->cid = input('cid');
        $this->vip = model('Vip');
        $this->diamond = model('Diamond');
        $this->gifts = model('Gifts');
    }

    /**
     * 导入
     * @return mixed
     */
    public function import(){
        return parent::import();
    }

    /**
     * 查看vip订单
     */
    public function vip()
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

            $status['is_del'] = 0; // 非回收站
            $total = $this->vip
                ->where($status)
                ->order($sort,$order)
                ->count();

            $list = $this->vip
                ->where($status)
                ->order($sort,$order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 查看钻石订单
     */
    public function diamond()
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

            $status['is_del'] = 0; // 非回收站
            $total = $this->diamond
                ->where($status)
                ->order($sort,$order)
                ->count();

            $list = $this->diamond
                ->where($status)
                ->order($sort,$order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 查看钻石订单
     */
    public function gifts()
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

            $status['is_del'] = 0; // 非回收站
            $total = $this->gifts
                ->where($status)
                ->order($sort,$order)
                ->count();

            $list = $this->gifts
                ->where($status)
                ->order($sort,$order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

   
    /**
     * 软删除
     * @param string $ids
     */
    public function softDelete($ids = "")
    {
        if ($ids)
        {
            // $where['id'] = ['in', $ids];
            $where['id'] = $ids;
            $tb = input('tb');
            switch ($tb) {
                case 'vip':
                    $this->table = model('Vip');
                    break;
                case 'diamond':
                    $this->table = model('Diamond');
                    break;
                case 'gifts':
                    $this->table = model('Gifts');
                    break;
                
                default:
                    break;
            }
            $count = $this->table->where($where)->update(['is_del' => 1]);
            if ($count)
            {
                $this->success();
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     *  回收站内容
     */
    public function soft()
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

    // 公共信息整理
    public function handles($params,$userids){
        $params = $this->artist_handles($params,$userids);
        return $params;
    }

}
