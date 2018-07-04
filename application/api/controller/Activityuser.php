<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Activityuser extends Api
{
    
    /**
     * ActivityUser模型对象
     */
    protected $model    = null;
    protected $validate = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model    = model('ActivityUser');
        $this->validate = validate('ActivityUser');
    }

    /**
     * 会员报名接口
     * @return \think\response\Json
     */
    public function toSignUp()
    {
        $uid = input('uid');
        $aid = input('aid');

        if ($this->model->where('uid', $uid)->where('aid', $aid)->find()){
            return api_json('1', '已报名', null);
        }

        $data = [
            'uid'       => $uid,
            'aid'       => $aid,
            'add_time'  => time()
        ];

        if (!$this->validate->check($data)){
            return api_json('1', $this->validate->getError(), null);
        }

        if ($this->model->save($data)){
            return api_json('0', '报名成功', $data);
        }

        return api_json('1', '报名失败', null);
    }


    public function getList()
    {
        $page   = input('page') ? input('page') : 1;
        $uid    = input('uid') ? input('uid') : 0;

        $offset = ($page - 1) * 10;
        $limit  = $page * 10;

        $total = $this->model
            ->where('uid', $uid)
            ->count();

        $data = $this->model->getUserActivity($uid, $offset, $limit);

        $list = $this->model->checkUserActivity($data, $uid);

        $result = array("total" => $total, "rows" => $list);

        return api_json('0', 'ok', $result);
    }


}
