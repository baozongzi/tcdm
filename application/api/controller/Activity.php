<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Activity extends Api
{
    
    /**
     * Activity模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Activity');

    }

    /**
     * 查看
     */
    public function index()
    {
        $page   = input('page') ? input('page') : 1;
        $uid    = input('uid') ? input('uid') : 0;

        $offset = ($page - 1) * 10;
        $limit  = $page * 10;

        $total = $this->model
            ->where('top', 0)
            ->count();

        $list = $this->model
            ->where('top', 0)
            ->field('id, type, title, time, image, describe')
            ->limit($offset, $limit)
            ->select();

        $ActivityUser = model('ActivityUser');
        $list = $ActivityUser->checkUserActivity($list, $uid);

        $result = array("total" => $total, "rows" => $list);

        return api_json(0, 'ok', $result);
    }

    public function detail($ids = null)
    {
        $data = $this->model->get(['id' => $ids]);
        if (!$data)
            $this->error(__('No Results were found'));
        $this->view->assign("data", $data);
        return $this->view->fetch();
    }

    /**
     * 获取置顶活动接口
     * @return \think\response\Json
     */
    public function getTopActive()
    {
        $uid    = input('uid') ? input('uid') : 0;
        $list = $this->model->field('id, type, title, time, image, describe')->where('top', '1')->select();
        $ActivityUser = model('ActivityUser');
        $list = $ActivityUser->checkUserActivity($list, $uid);
        return api_json('0', 'ok', $list);
    }
}
