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

class Index extends Api
{

    /**
     * Teacher模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->AuthRule = model('AuthRule');
        $token = cookie('access_token');
        $this->row = input('row');
        $this->row = base64_decode($this->row);
        $this->row = json_decode($this->row);
        if(isset($this->row->urlParams)){
            $this->row = $this->row->urlParams;
        }
        $this->page   = isset($this->row->page) ? $this->row->page : 1;
        $this->offset = ($this->page - 1) * 2;
        $this->limit  = $this->page * 2;
        // $this->rule($token,$userid);
        $this->website = model('Config')->where('name', 'website')->value('value');
        // $this->model = model('Video');

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

    public function index(){
        // $result = $this->model->field('id,name,inputtime,thumb')->select();
        // banner
        $banner = $this->init_thumbs(Db::table('fa_banner')->where('is_index = 1')->field('id,vid,title,thumb,model,cid,is_link,url')->order('updatetime desc,id desc')->limit(3)->select());
        foreach ($banner as $b => $br) {
            if($banner[$b]['is_link'] == '0'){
                $banner[$b]['url'] = $this->website.'/api/index/showbanner';
            }
            if($banner[$b]['is_link'] == '2'){
                $vid = $banner[$b]['vid'];
                unset($banner[$b]['vid']);
                $banner[$b]['id'] == $vid;
            }
            if($banner[$b]['is_link'] == '3'){
                unset($banner[$b]['vid']);
                $banner[$b]['url'] = $this->website.'/index/index/advban/id/'.$banner[$b]['id'];
            }
        }
        $advertise = $this->init_thumbs(Db::table('fa_ad')->field('id,title,thumb,description,is_link,url')->order('updatetime desc,id desc')->limit(1)->find());
        //成语故事
        $story = $this->init_thumbs(Db::table('fa_story')->where('status = 1 AND is_index = 1')->field('id,title,thumb,view')->order('updatetime desc,id desc')->limit(5)->select());
        foreach ($story as $sy => $sys) {
            $story[$sy]['model'] = 'story';
        }
        //健康养生
        $health1 = Db::table('fa_health_interview')->where('status = 1 AND is_index = 1')->field('id,title,thumb,inputtime,description,view,cid')->order('updatetime desc,id desc')->limit(1)->find();//养生访谈
        $health1['description'] = mb_substr($health1['description'],0,10,'utf-8')."...";
        $health1['model'] = 'health';
        $health1['comment'] = Db::table('fa_health_interview_comment')->where('vid = '.$health1['id'])->count();

        $health2 = Db::table('fa_health_story')->where('status = 1 AND is_index = 1')->field('id,title,thumb,inputtime,description,view,cid')->order('updatetime desc,id desc')->limit(1)->find();//养生故事
        $health2['description'] = mb_substr($health2['description'],0,10,'utf-8')."...";
        $health2['model'] = 'health';
        $health2['comment'] = Db::table('fa_health_story_comment')->where('vid = '.$health2['id'])->count();

        $health = $this->init_thumbs(array($health1,$health2));
        //城市文化
        $city = $this->init_thumbs(Db::table('fa_city')->where('status = 1 AND is_index = 1')->field('id,title,thumb,inputtime,cid')->order('updatetime desc,id desc')->limit(4)->select());
        foreach ($city as $c => $cy) {
            $city[$c]['model'] = 'city';
        }
        //最新综艺
        $variety = $this->init_thumbs(Db::table('fa_variety')->where('status = 1 AND is_index = 1')->field('id,title,thumb,inputtime,description')->order('updatetime desc,id desc')->limit(3)->select());
        foreach ($variety as $vt => $vts) {
            $variety[$vt]['description'] = mb_substr($variety[$vt]['description'],0,10,'utf-8')."...";
            $variety[$vt]['model'] = 'variety';
        }
        //艺人包装
        $match = $this->init_thumbs(Db::table('fa_match')->where('status = 1 AND is_index = 1')->field('id,title,thumb,starttime,endtime')->order('updatetime desc,id desc')->limit(3)->select());
        foreach ($match as $m => $mv) {
            $match[$m]['count'] = Db::table('fa_match_user')->where('match_id = '.$mv['id'])->count();
            $match[$m]['model'] = 'match';
        }

        $result['banner'] = $banner;
        $result['advertise'] = $advertise;
        $result['story'] = $story;
        $result['health'] = $health;
        $result['city'] = $city;
        $result['variety'] = $variety;
        $result['match'] = $match;
        $status = '1';
        $mes = '获取成功😏';
        $res = $this->json_echo($status,$mes,$result);
        return $res;
    }

    public function showbanner(){
        $is_link = $this->row->is_link;
        $table = $this->row->model;
        $cid = $this->row->cid;

        $id = $this->row->vid;//视频id
        $userid = $this->row->userid;//当前登录的用户
        if($is_link == '3'){
            $data = $this->init_thumbs(Db::table('fa_banner')->where('id = '.$id)->find());
            $status = '1';
            $mes = '获取成功😏';
            $res = $this->json_echo($status,$mes,$data);
            return $res;
        }
        //数据详情
        if($cid == 0){
            $data = $this->init_thumbs(Db::table('fa_'.$table)->where('id = '.$id)->find());
        }else{
            $data = $this->init_thumbs(Db::table('fa_'.$table)->where('id = '.$id.' and cid = '.$cid)->find());
        }
        // $data['head'] = $this->website.$data['head'];
        // 一级栏目查询
        $model = $this->AuthRule->where("tables = '".$table."'")->find();
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
        Db::table('fa_'.$table)->where('id = '.$id)->update($update);

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

    
    

}
