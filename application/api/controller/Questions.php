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
class Questions extends Api
{
    
    /**
     * Questions模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Questions');

    }

    public function getChaptersQuestion()
    {
        $ids = input('ids');

        $result = $this->model->getChaptersQuestionList($ids);

        return api_json('0', 'ok', $result);
    }

    public function getAllList()
    {
        return $this->model->where('status', 1)->select();
    }

}
