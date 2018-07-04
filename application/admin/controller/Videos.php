<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Controller;
use fast\Tree;
use think\Cache;
use think\Request;
use think\Db;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Videos extends Backend
{
    
    /**
     * Videos模型对象
     */
    protected $model = null;

    protected $searchFields = 'id,name,pid,price,inputtime,star';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Videos');
        $this->table = 'Videos';

        parent::_initialize();
        $this->models = model('Vclassify');
        // 必须将结果集转换为数组
        $ruleList = collection($this->models->order('weigh', 'desc')->select())->toArray();
        foreach ($ruleList as $k => &$v)
        {
            $v['title'] = __($v['title']);
        }
        unset($v);
        Tree::instance()->init($ruleList);
        $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        $ruledata = [0 => __('None')];
        $this->view->assign('ruledata', $ruledata);
    }

    /**
     * 导入
     * @return mixed
     */
    public function import(){
        return parent::import();
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
            $status['status'] = 1; // 非回收站
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

            foreach ($list as $l => $lt) {
                $gd_pid = $this->models->where('id = '.$lt['gd_pid'])->field('title')->find();
                $pid = $this->models->where('id = '.$lt['pid'])->field('title')->find();
                $list[$l]['gd_pid'] = $gd_pid['title'];
                $list[$l]['pid'] = $pid['title'];
            }

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
            $user = $this->request->post('user/a');

            $params['inputtime'] = time();
            $result = $this->artist_handles('',$params,$user,$this->table);
            if ($result)
            {
                // $this->model->save($params);
                $this->success();
            }
            $this->error();
        }
        $list = $this->rulelist;
        $artists = "";
        $row['is_fee'] = "1";
        $row['price'] = "";
        $row['video'] = "";
        $this->view->assign("row", $row);
        $this->view->assign("artists", $artists);
        $this->view->assign("list",$list);
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
            $user = $this->request->post('user/a');
            
            $result = $this->artist_handles($ids,$params,$user,$this->table);
            if ($result)
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
        $list = $this->rulelist;
        // 判断是否选择了艺人
        if($row['artist']){
            $row['artist'] = unserialize($row['artist']);
            foreach ($row['artist'] as $r => $v) {
                unset($row['artist']);
                $row['artists'][$r]['userid'] = $v;
                $user = Db::table("fa_user")->field('id,head,normal_name')->where('id = '.$v['userid'])->find();
                $artists[$r]['userid'] = $user['id'];
                $artists[$r]['head'] = $user['head'];
                $artists[$r]['normal_name'] = $user['normal_name'];
                $artists[$r]['cosplay'] = $v['cosplay'];
            }
        }else{
            $artists = "";
        }
        
        $this->view->assign("artists", $artists);
        $this->view->assign("list",$list);
        $this->view->assign("row", $row);
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
            $where['id'] = ['in', $ids];
            $count = $this->model->where($where)->update(['status' => 0]);
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

}
