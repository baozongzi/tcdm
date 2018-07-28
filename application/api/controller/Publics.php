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

class Publics extends Api
{

	public function _initialize()
    {
        parent::_initialize();
        $this->AuthRule = model('AuthRule');
        // 验证token
        $token = cookie('access_token');
        $this->row = input('row');
        $this->row = base64_decode($this->row);
        $this->row = json_decode($this->row);
        if(isset($this->row->urlParams)){
            $this->row = $this->row->urlParams;
        }
        if(isset($this->row->userid) && $this->row->userid !== '-1'){
            $this->userid = $this->row->userid;
            $this->rule($token,$this->userid);
        }
        $this->table = $this->row->model;
        $this->cid = $this->row->cid;
        if($this->table == 'health'){
            // 判断数据表
            switch ($this->cid) {
            case '1':
                $this->model = model("Hinterview");
                $this->table = 'health_interview';
                $this->tab = 'health';
                break;
            case '2':
                $this->model = model("Hstory");
                $this->table = 'health_story';
                $this->tab = 'health';
                break;
            case '3':
                $this->model = model("Hproduct");
                $this->table = 'health_product';
                $this->tab = 'health';
                break;
            case '4':
                $this->model = model("Hcommon");
                $this->table = 'health_common';
                $this->tab = 'health';
                break;
            default:
                
                break;
            }
        }else{
            $this->model = model($this->table);
            $this->table = $this->table;
            $this->tab = $this->table;
        }

        // echo "<pre>";
        // print_r($this->row);
        // die;
        $this->page   = isset($this->row->page) ? $this->row->page : 1;
        $this->offset = ($this->page - 1) * 2;
        $this->limit  = $this->page * 2;
        $this->website = model('Config')->where('name', 'website')->value('value');
    }
	// 列表页
    public function banner(){
        $banner = $this->init_thumbs(Db::table('fa_banner')->field('id,title,thumb,model,cid,url')->where("model = '$this->table'")->order('inputtime desc')->limit(3)->select());

        $result['banner'] = $banner;
        
        $status = '1';
        $mes = '获取成功😏';
        $res = $this->json_echo($status,$mes,$result);
        return $res;
    }

    public function shows(){

        $id = $this->row->vid;//视频id
        //数据详情
        if(isset($this->cid)){
            $data = $this->init_thumbs(Db::table('fa_'.$this->table)->where('id = '.$id.' and cid = '.$this->row->cid)->field('id,title,description,content,view,thumb,is_fee,price,video,artist,team')->find());
        }else{
            $data = $this->init_thumbs(Db::table('fa_'.$this->table)->where('id = '.$id)->field('id,title,description,content,view,thumb,is_fee,price,video,artist,team')->find());
        }
        
        // 一级栏目查询
        $model = $this->AuthRule->where("tables = '".$this->table."'")->find();
        // 艺人信息处理
        $res = $this->artist_show($data);
        $res['url'] = $this->website;
        $res['team'] = unserialize($res['team']);
        // 视频解密处理
        // $res['video'] = $this->base64_de($res['video']);
        if($this->userid){
            $userid = $this->userid;//当前登录的用户
            // 判断视频是否收费或者用户是否为vip
            $userpay = $this->is_fee($id,$userid,$data['is_fee'],$data['price'],$model['price']);
            // 观看进度
            $res['percentage'] = $this->history($userid,$id,$model['tables']);
            // 是否收藏
            $res['is_collected'] = $this->collection($userid,$id,$model['tables']);
        }
        
        // 评论
        $comment = $this->comment($id,$model['tables'],$this->offset, $this->limit);
        // 点击量更新
        $data['view'] = $data['view'] + 1;
        $update['view'] = $data['view'];
        $this->model->where('id = '.$id)->update($update);
        $history['userid'] = $this->userid;
        $history['vid'] = $res['id'];
        $history['tables'] = $this->table;
        $history['title'] = $res['title'];
        $history['updatetime'] = time();
        if(!Db::table('fa_history')->where('userid = '.$this->userid." AND vid = ".$res['id']." AND tables = '".$this->table."'")->find()){
            Db::table('fa_history')->insert($history);
        }
        
        $res['comment'] = $comment;
        if($res){
            return $this->json_echo('1','获取成功😏',$res);
            // return api_json('1', 'OK', $res);
        }else{
            return $this->json_echo('0','内容飞走了😏',array());
            // return api_json('0', 'ERROR', $err);
        }
    }

    // 收藏
    public function collectioned(){
        $data = $this->collectionsed($this->row,$this->table,$this->model,$models = $this->tab);
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