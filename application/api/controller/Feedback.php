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
class Feedback extends Api
{
    
    /**
     * Feedback模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Feedback');

    }

    public function addData()
    {
        $data = [
            'uid'       => input('uid'),
            'title'     => input('title'),
            'content'   => input('content'),
            'add_time'  => time()
        ];

        if ($this->model->save($data)){
            return api_json('0', 'ok', $data);
        }
        return api_json('1', '提交失败', $data);
    }

    

}
