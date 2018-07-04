<?php

namespace app\api\controller\statistics;

use app\common\controller\Api;
use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Paper extends Api
{
    
    /**
     * StatisticsPaper模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('StatisticsPaper');

    }

    /**
     * 同步用户考试统计接口
     * @return \think\response\Json
     */
    public function savePapersUser()
    {
        $data = [
            'uid'               => input('uid'),
            'sum_questions'     => input('sum_questions'),
            'done_questions'    => input('done_questions'),
            'accuracy'          => input('accuracy'),
            'error_questions'   => input('error_questions')
        ];
        if ($this->model->save($data)){
            return api_json('0', '同步成功', null);
        }
        return api_json('1', '同步失败', null);
    }

}
