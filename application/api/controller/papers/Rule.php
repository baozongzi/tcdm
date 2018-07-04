<?php

namespace app\api\controller\papers;

use app\common\controller\Api;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Rule extends Api
{
    
    /**
     * PapersRule模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('PapersRule');

    }

    /**
     * 获取
     */
    public function getPaperRuleList()
    {
        return $this->model->select();
    }


}
