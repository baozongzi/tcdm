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
class Banner extends Backend
{
    
    /**
     * Videos模型对象
     */
    protected $model = null;

    protected $searchFields = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Banner');
        $this->AuthRule = model('AuthRule');
        // 必须将结果集转换为数组
        $ruleList = collection($this->AuthRule->where('is_fee = 1')->order('id', 'asc')->select())->toArray();
        
        foreach ($ruleList as $k => &$v)
        {
            $v['title'] = __($v['title']);
        }
        unset($v);
        $this->rulelist = $this->tree($ruleList);
        // Tree::instance()->init($ruleList);
        // $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        // $ruledata = [0 => __('None')];
        foreach ($this->rulelist as $k => &$v)
        {
            if (!$v['status'])
                continue;
            // $ruledata[$v['id']] = $v['title'];
            
            $ruledata[$k]['pid'] = $v['pid'];
            $ruledata[$k]['table'] = $v['tables'];
            $ruledata[$k]['title'] = $v['title'];
            $ruledata[$k]['sign'] = $v['sign'];
        }
        // echo "<pre>";
        // print_r($ruledata);
        // die;
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
            if($params['is_link'] == '1'){
                $params['title'] = $params['mytitle'];
            }
            unset($params['mytitle']);
            $params['inputtime'] = time();
            if ($params)
            {
                $this->model->save($params);
                $this->success();
            }
            $this->error();
        }
        $row['is_link'] = '1';
        $row['url'] = '';
        $row['table'] = '';
        $row['vid'] = '';
        $row['catname'] = '';
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
            if($params['is_link'] == '1'){
                $params['title'] = $params['mytitle'];
            }
            unset($params['mytitle']);
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
        // echo "<pre>";
        // print_r($row);
        // die;
        $this->assign('row',$row);
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

    // 搜索栏目内容
    public function search_catname(){
        $table = input('table');
        $table_name = input('table_name');
        $content = Db::table("fa_".$table)->where('status = 1')->field('id,title')->select();
        foreach ($content as $c => $value) {
            $content[$c]['catname'] = $table_name;
        }
        exit(json_encode($content));
    }


}
