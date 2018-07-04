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
class Papers extends Api
{
    
    /**
     * Activity模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Papers');

    }

    /**
     * 同步用户试卷试题接口
     * @return \think\response\Json
     */
    public function savePapersUser()
    {
        $data = json_decode(input('data'), true);

        foreach ($data as $k=>$v){
            $papersQuestion = $v['data'];
            $papers = $v;
            unset($papers['data']);

            if ($res = $this->model->save($papers)){
                foreach ($papersQuestion as $key=>&$value) {
                    $value['pid'] = $res;
                }
                if (!model('PapersQuestions')->saveAll($papersQuestion)){
                    return api_json('1', '同步失败', null);
                }
            }
        }
        return api_json('0', '同步成功', null);
    }

    /**
     * 获取用户考试试题记录
     * @return \think\response\Json
     */
    public function getPaperQuestionsUser()
    {
        $uid = input('uid');

        $res = $this->model->with('questions')->where('uid', $uid)->select();
        return api_json('0', 'ok', $res);
    }
    

}
