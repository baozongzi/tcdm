<?php

namespace app\api\controller;

use app\common\controller\Api;

use think\Controller;
use think\Request;
use think\Db;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Questionsuser extends Api
{
    
    /**
     * QuestionsUser模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('QuestionsUser');
    }

    /**
     * 同步用户错题收藏记录接口
     * @return \think\response\Json
     */
    public function saveQuestionsUser()
    {
        $uid        = input('uid');
        $data       = json_decode(input('data'), true);

        foreach ($data as $k=>&$v){
            $v['uid'] = $uid;
        }

        $this->model->startTrans();
        try{
            // 提交事务
            $this->model->commit();
            $this->model->where('uid', $uid)->delete();
            $this->model->saveAll($data);
            return api_json('0', '同步成功', null);
        } catch (\Exception $e) {
            // 回滚事务
            $this->model->rollback();
            return api_json('1', '同步失败', null);
        }
    }

    public function getQuestionsUser()
    {
        $uid = input('uid');
        return api_json('0', 'ok', $this->model->where('uid', $uid)->select());
    }

}
