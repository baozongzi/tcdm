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

class Crowdfunding extends Api
{

    /**
     * Teacher模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Crowdfunding');
        $this->catname = 'Story';
        $this->table = 'crowdfunding';
        $this->AuthRule = model('AuthRule');
        $this->page   = input('page') ? input('page') : 1;
        $this->offset = ($this->page - 1) * 2;
        $this->limit  = $this->page * 2;

        $this->post = $this->ispost();
        if($this->post['count'] == '0'){
            $err['id'] = '0';
            $json_arr = array('status'=>1,'msg'=>'请按套路出牌😏','result'=>$err );
            $json_str = json_encode($json_arr);
            exit($json_str);
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
        $result = Db::table('fa_crowdfunding')->field('id,title,inputtime,thumb')->where('status = 1')->limit($this->offset, $this->limit)->select();
        // echo "<pre>";
        // print_r($result);
        // die;
        //图片格式化
        $result = $this->init_thumbs($result);
        $status = '1';
        $mes = '获取成功😏';
        $res = $this->json_echo($status,$mes,$result);
        return $res;
        // return api_json('0', 'OK', $result);
    }

    // 查看详情
    public function show(){
        $id = $this->post['vid'];//视频id
        $userid = $this->post['userid'];//当前登录的用户
        //数据详情
        $data = $this->model->where('id = '.$id)->field('id,title,total_money,success,person_num,successed,description,content,artist')->find();
        switch ($data['successed']){
            case '1':
                $data['successed'] = '火热进行中^_^';
                break;
            case '2':
                $data['successed'] = '众筹成功^_^';
                break;
            case '0':
                $data['successed'] = '众筹失败(╥╯^╰╥)';
                break;
            default:
                return "";
        }
        // 一级栏目查询
        $model = $this->AuthRule->where("tables = '".$this->table."'")->find();
        // 判断视频是否收费或者用户是否为vip
        // $userpay = $this->is_fee($id,$userid,$data['is_fee'],$data['price'],$model['price']);
        // 艺人信息处理
        $res = $this->artist_show($data);
        // 视频解密处理
        // $res['video'] = $this->base64_de($res['video']);
        // 观看进度
        // $res['percentage'] = $this->history($userid,$id,$model['tables']);
        // 是否收藏
        // $res['is_collected'] = $this->collection($userid,$id,$model['tables']);
        // 评论
        // $comment = $this->comment($userid,$id,$model['tables'],$this->offset, $this->limit);

        // $res['comment'] = $comment;
        if($res){
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
