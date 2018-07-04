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
class Message extends Api
{
    
    /**
     * Message模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Message');

    }

    public function getMessage()
    {
        $type = input('type');
        $res  = $this->model->getMessageRuleList($type);
        if ($res){
            return api_json('0', 'ok', $res);
        }
        return api_json('1', '数据不存在', null);
    }


}
