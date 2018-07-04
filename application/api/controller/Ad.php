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
class Ad extends Api
{
    
    /**
     * Ad模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Ad');
    }

    public function getTypeAdList()
    {
        $where['type'] = input('type');
        $where['page'] = input('page');
        $res = collection($this->model->where($where)->select())->toArray();

        if ($res){
            return api_json('0', 'ok', $res);
        }
        return api_json('1', '数据不存在', null);
    }
}
