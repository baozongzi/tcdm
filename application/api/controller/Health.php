<?php
/**
 * Created by PhpStorm.
 * User: wangcailin
 * Date: 2017/10/30
 * Time: 下午4:42
 */
namespace app\api\controller;

use app\common\controller\Api;
use think\Validate;
use app\common\library\Alidayu;
use think\Db;
use think\Request;

class Health extends Api
{

    /**
     * Teacher模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->catname = 'Health';
        $this->AuthRule = model('AuthRule');
        $this->page   = input('page') ? input('page') : 1;
        $this->offset = ($this->page - 1) * 10;
        $this->limit  = $this->page * 10;

        $this->cid = input('post.cid');
        // 判断数据表
        switch ($this->cid) {
        case '1':
            $this->model = model("Hinterview");
            $this->table = 'health_interview';
            break;
        case '2':
            $this->model = model("Hstory");
            $this->table = 'health_story';
            break;
        case '3':
            $this->model = model("Hproduct");
            $this->table = 'health_product';
            break;
        case '4':
            $this->model = model("Hcommon");
            $this->table = 'health_common';
            break;
        default:
            return false;
        }

        $this->post = $this->ispost();
        if($this->post['count'] == '0'){
            $err['id'] = '0';
            $message = '请按套路出牌😏';
            $this->encode($err,$message);
        }

        // print_r($_SERVER);
        // 加密操作
        // $token = cookie('access_token');
        // $row = input('row/a');
        // $this->string = 'mcy-zgys';
        // $this->row = input('row/a');
        // $message = array(
        //     'mobile'    => $this->row['mobile'],
        //     'unique'    => $this->row['unique'],
        // );
        // $this->access_token = $this->create_token($message);
        // $this->update = array(
        //     'mobile'        => $this->row['mobile'],
        //     'unique'    => $this->row['unique'],
        //     'access_token'  => $this->access_token,
        // );
    }
    // 列表页
    public function index(){
        $result = $this->init_time($this->model->field('id,title,inputtime,thumb')->where('status = 1')->limit($this->offset, $this->limit)->select());
        // $res = $this->artist_show($result);
        return api_json('0', 'OK', $result);
    }

    // 查看详情
    public function show(){
        $id = $this->post['vid'];//视频id
        $userid = $this->post['userid'];//当前登录的用户

        //数据详情
        $data = $this->model->where('id = '.$id)->find();
        // 一级栏目查询
        $model = $this->AuthRule->where("tables = '".$this->table."'")->find();
        // 判断视频是否收费或者用户是否为vip
        $userpay = $this->is_fee($id,$userid,$data['is_fee'],$data['price'],$model['price']);
        // 艺人信息处理
        $res = $this->artist_show($data);
        // 视频解密处理
        // $res['video'] = $this->base64_de($res['video']);
        // 观看进度
        $res['percentage'] = $this->history($userid,$id,$model['tables']);
        // 是否收藏
        $res['is_collected'] = $this->collection($userid,$id,$model['tables']);
        // 评论
        $comment = $this->comment($userid,$id,$model['tables'],$this->offset, $this->limit);

        $res['comment'] = $comment;
        if($userpay){
            return api_json('1', 'OK', $res);
        }else{
            $err['id'] = $data['id'];
            return api_json('0', 'ERROR', $err);
        }
    }

    // 评论接口
    public function comments(){
        $data = input('');
        $user = Db::table('fa_user')->where("id = ".$data['userid'])->field('nickname,head')->find();
        $data['inputtime'] = strtotime(date("Y-m-d",time())." ".date('H').":0:0");
        
        $data['nickname'] = $user['nickname'];
        $data['head'] = $user['head'];
        $res = Db::table('fa_'.$this->table.'_comment')->insert($data);
        if($res){
            $message = '评论成功';
            $this->encode($data,$message);
        }
    }


}
