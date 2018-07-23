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
class Crowdfunding extends Backend
{
    
    /**
     * Videos模型对象
     */
    protected $model = null;

    protected $searchFields = 'id,name,pid,price,inputtime,star';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Crowdfunding');
        $this->table = 'Crowdfunding';
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
            return $this->index_soft('1');
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
            $params = $this->request->post("row/a", [], 'strip_tags');
            $params['inputtime'] = time();
            $params['updatetime'] = time();
            $params['endtime'] = strtotime($params['endtime']);
            $user = $this->request->post('user/a');
            $info = $this->request->post('info/a');
            $belong_to = explode('_',$params['belong_to']);
            $params['belong_to'] = $belong_to[0];
            $params['model'] = $belong_to[1];
            
            $result = $this->artist_handles('',$params,$user,$info,$this->table);
            if ($params)
            {
                $this->model->save($params);
                $this->success();
            }
            $this->error();
        }
        $artists = "";
        $team = "";
        $row['is_fee'] = "1";
        $row['price'] = "";
        $row['video'] = "";
        $this->view->assign("row", $row);
        $this->view->assign("artists", $artists);
        $this->view->assign("team", $team);
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
            $params['endtime'] = strtotime($params['endtime']);
            $user = $this->request->post('user/a');
            $info = $this->request->post('info/a');
            $belong_to = explode('_',$params['belong_to']);
            $params['belong_to'] = $belong_to[0];
            $params['model'] = $belong_to[1];
            
            $result = $this->artist_handles($ids,$params,$user,$info,$this->table);
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
        if($row['team']){
           $team = $row['team'] = unserialize($row['team']);
        }else{
            $team = "";
        }
        $template = "edit";
        $this->view->assign("row", $row);
        $this->view->assign("artists", $artists);
        $this->view->assign("team", $team);
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
            return $this->index_soft('0');
        }
        return $this->view->fetch();
    }

    // 查看众筹人员信息
    public function show($ids = NULL){
        $ids = Request::instance()->param('ids');
        print_r($ids);
        echo "我成功了^_^";
    }

    public function index_soft($status = '1'){
        //如果发送的来源是Selectpage，则转发到Selectpage
        if ($this->request->request('pkey_name'))
        {
            return $this->selectpage();
        }
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        $status = ['status' => $status];
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
            switch ($lt['successed']){
                case '1':
                    $lt['successed'] = '火热进行中^_^';
                    break;
                case '2':
                    $lt['successed'] = '众筹成功^_^';
                    break;
                case '0':
                    $lt['successed'] = '众筹失败(╥╯^╰╥)';
                    break;
                default:
                    return "";
            }
        }
        $result = array("total" => $total, "rows" => $list);
        return json($result);
    }

}
