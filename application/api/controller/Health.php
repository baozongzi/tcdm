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
        $token = cookie('access_token');
        $this->row = input('row');
        $this->row = base64_decode($this->row);
        $this->row = json_decode($this->row);
        $this->userid = $this->row->userid;
        $this->cid = $this->row->cid;
        $this->page   = isset($this->row->page) ? $this->row->page : 1;
        $this->offset = ($this->page - 1) * 10;
        $this->limit  = $this->page * 10;
        $this->website = model('Config')->where('name', 'website')->value('value');

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

        $this->rule($token,$this->userid);

    }
    // 列表页
    public function index(){
        $banner = $this->init_thumbs(Db::table('fa_banner')->field('id,title,thumb,model,cid,url')->where("model = 'health' and cid = $this->cid")->order('inputtime desc')->limit(3)->select());
        $health = $this->init_thumbs(Db::table("fa_".$this->table)->field('id,title,inputtime,thumb,view')->where('status = 1')->order('id desc')->limit($this->offset, $this->limit)->select());
        
        $result['banner'] = $banner;
        $result['health'] = $health;
        // $res = $this->artist_show($result);
        $status = '1';
        $mes = '获取成功😏';
        $res = $this->json_echo($status,$mes,$result);
        return $res;
    }

    // 查看详情
    public function show(){
        $id = $this->row->vid;//视频id
        $userid = $this->userid;//当前登录的用户

        //数据详情
        $data = $this->init_thumbs($this->model->where('id = '.$id)->find());
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
        // 点击量更新
        $data['view'] = $data['view'] + 1;
        $update['view'] = $data['view'];
        $this->model->where('id = '.$id)->update($update);
        $res['comment'] = $comment;
        if($res){
            $status = '1';
            $mes = '获取成功😏';
            $res = $this->json_echo($status,$mes,$res);
            return $res;
            // return api_json('1', 'OK', $res);
        }else{
            $err['id'] = $data['id'];
            $status = '1';
            $mes = '获取成功😏';
            $res = $this->json_echo($status,$mes,$err);
            return $res;
            // return api_json('0', 'ERROR', $err);
        }
    }

    // 评论接口
    public function comments(){
        $userid = $this->row->userid;//当前登录的用户
        $user = Db::table('fa_user')->where("id = ".$userid)->field('nickname,head')->find();
        $data['inputtime'] = strtotime(date("Y-m-d",time())." ".date('H').":0:0");
        $data['nickname'] = $user['nickname'];
        $data['head'] = $this->website.$user['head'];
        $data['userid'] = $userid;
        $data['vid'] = $this->row->vid;
        $data['content'] = $this->row->content;
        $res = Db::table('fa_'.$this->table.'_comment')->insert($data);
        if($res){
            $status = '1';
            $mes = '评论成功😏';
            $res = $this->json_echo($status,$mes,$data);
            return $res;
        }
    }

    // 收藏
    public function collectioned(){
        $data = $this->collectionsed($this->row,$this->table,$this->model,$models = 'health');
        if($data == 0){
            $status = '0';
            $mes = '已收藏过了😏';
            $res = $this->json_echo($status,$mes,$data);
            return $res;
        }else{
            $status = '1';
            $mes = '成功😏';
            $res = $this->json_echo($status,$mes,$data);
            return $res;
        }
    }


}
