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
class Questions extends Api
{
    
    /**
     * StatisticsQuestions模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('StatisticsQuestions');

    }

    /**
     * 同步用户试题统计接口
     * @return \think\response\Json
     */
    public function saveQuestionsUser()
    {
        $data = [
            'uid'               => input('uid'),
            'max_score'         => input('max_score'),
            'sum_papers_num'    => input('sum_papers_num'),
            'average'           => input('average'),
            'sum_pass'          => input('sum_pass')
        ];
        if ($this->model->save($data)){
            return api_json('0', '同步成功', null);
        }
        return api_json('1', '同步失败', null);
    }

}
