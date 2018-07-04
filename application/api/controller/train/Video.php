<?php

namespace app\api\controller\train;

use app\common\controller\Api;

use think\Controller;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Video extends Api
{
    
    /**
     * TrainVideo模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('TrainVideo');
    }

    /**
     * 查看
     */
    public function index()
    {
        $page   = input('page') ? input('page') : 1;

        $offset = ($page - 1) * 10;
        $limit  = $page * 10;

        $total = $this->model
            ->count();

        $list = $this->model
            ->order('weigh', 'DESC')
            ->limit($offset, $limit)
            ->select();

        $result = array("total" => $total, "rows" => $list);

        return api_json(0, 'ok', $result);
    }

    /**
     * 频道页获取指定视频和直播
     * @return \think\response\Json
     */
    public function getVideoLive()
    {
        $video = $this->model->getVideoWeigh();
        $live  = model('TrainLive')->getLiveWeigh();
        $data = [
            'video' => $video,
            'live'  => $live
        ];
        return api_json('0', 'ok', $data);
    }
    

}
