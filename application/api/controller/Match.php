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

class Match extends Api
{

    /**
     * Teacher模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Match');
        $this->catname = 'Match';
        $this->table = 'match';
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
        $this->page   = isset($this->row->page) ? $this->row->page : 1;
        $this->offset = ($this->page - 1) * 10;
        $this->limit  = $this->page * 10;
        $this->website = model('Config')->where('name', 'website')->value('value');
        // $method = $this->ispost();
        // if($method == POST){
        //     $err['id'] = '0';
        //     $json_arr = array('status'=>1,'msg'=>'请按套路出牌😏','data'=>$err );
        //     $json_str = json_encode($json_arr);
        //     exit($json_str);
        // }

    }
    // 列表页
    public function index(){
        // $banner = $this->init_thumbs(Db::table('fa_banner')->field('id,title,thumb,model,cid,url')->where("model = '$this->table'")->order('inputtime desc')->limit(3)->select());
        $match = $this->init_thumbs(Db::table("fa_".$this->table)->field('id,title,endtime,thumb,belong_to')->where('status = 1')->order('id desc')->limit($this->offset, $this->limit)->select());
        foreach ($match as $sy => $sys) {
            $match[$sy]['model'] = $this->table;
            $match[$sy]['count'] = Db::table('fa_match_user')->where('match_id = '.$match[$sy]['id'])->count();
        }
        // $result['banner'] = $banner;
        $result['match'] = $match;
        
        $status = '1';
        $mes = '获取成功😏';
        $res = $this->json_echo($status,$mes,$result);
        return $res;
    }

    // 排行榜列表
    public function rankings(){
        $banner = $this->init_thumbs(Db::table('fa_banner')->field('id,title,thumb,model,cid,url')->where("model = '$this->table'")->order('inputtime desc')->limit(3)->select());
        $match = $this->init_thumbs(Db::table("fa_".$this->table)->field('id,title,thumb')->where('status = 1')->order('id desc')->limit($this->offset, $this->limit)->select());
        foreach ($match as $sy => $sys) {
            $match[$sy]['model'] = $this->table;
            $match[$sy]['rankings'] = Db::table('fa_match_user')->alias('mu')
                                      ->join('fa_user u','mu.user_id = u.id')
                                      ->join('fa_match m','mu.match_id = m.id')
                                      ->where('match_id = '.$match[$sy]['id'])
                                      ->field('match_id,mu.user_id,mu.ballot,normal_name')
                                      ->order('ballot desc')
                                      ->limit(3)
                                      ->select();
        }
        $result['banner'] = $banner;
        $result['match'] = $match;
        
        $status = '1';
        $mes = '获取成功😏';
        $res = $this->json_echo($status,$mes,$result);
        return $res;
    }

    // 排行榜
    public function ranking(){
        $match = $this->init_thumbs(Db::table("fa_".$this->table)->field('id,title,thumb,endtime')->where('id = '.$this->row->vid)->order('id desc')->find());

        $match['surplusday'] = ($match['endtime'] - strtotime(date("Y-m-d",time()))) / 60 / 60 /24;
        $match['rankings'] = Db::table('fa_match_user')->alias('mu')
                              ->join('fa_user u','mu.user_id = u.id')
                              ->join('fa_match m','mu.match_id = m.id')
                              ->where('match_id = '.$match['id'])
                              ->field('match_id,mu.user_id,mu.ballot,normal_name,head')
                              ->order('ballot desc')
                              ->limit($this->offset, $this->limit)
                              ->select();
        foreach ($match['rankings'] as $m => $mr) {
            $match['rankings'][$m]['head'] = $this->website.$match['rankings'][$m]['head'];
        }
        $result = $match;
        $status = '1';
        $mes = '获取成功😏';
        $res = $this->json_echo($status,$mes,$result);
        return $res;
    }

    // 艺人详情界面
    public function showmuser(){
        $match = Db::table("fa_match_user")->where('match_id = '.$this->row->matchid." AND user_id = ".$this->row->muserid)->field('ballot')->find();
        $ballot = $match['ballot'];
        $rankings = Db::table("fa_match_user")->where('match_id = '.$this->row->matchid." AND ballot > ".$ballot)->count();
        $urank['ranking'] = $rankings + 1;
        Db::table("fa_match_user")->where('match_id = '.$this->row->matchid." AND user_id = ".$this->row->muserid)->update($urank);

        $muser = Db::table('fa_match_user')->alias('mu')
                  ->join('fa_user u','mu.user_id = u.id')//关联用户表
                  ->join('fa_match m','mu.match_id = m.id')//关联赛事表
                  ->join('fa_u_examine_basics ueb','mu.user_id = ueb.userid')//关联个人基本信息审核
                  ->join('fa_u_examine_video uev','mu.user_id = uev.userid')//关联个人信息视频审核
                  ->join('fa_u_examine_photo uep','mu.user_id = uep.userid')//关联个人信息视频审核
                  ->where('match_id = '.$this->row->matchid)
                  ->field('match_id,mu.user_id,mu.ballot,ueb.normal_name,head,mu.ranking,birthday,intro,video,uep.thumb')
                  ->order('ballot desc')
                  ->limit($this->offset, $this->limit)
                  ->find();

        $muser['url'] = $this->website;
        $muser['thumb'] = unserialize($muser['thumb']);
        foreach ($muser['thumb'] as $mt => $mts) {
            $muser['thumb'][$mt] = $this->website.$muser['thumb'][$mt];
        }
        $muser['head'] = $this->website.$muser['head'];
        $result = $muser;
        $status = '1';
        $mes = '获取成功😏';
        $res = $this->json_echo($status,$mes,$result);
        return $res;           
    }

    // 查看详情
    public function show(){
        $id = $this->row->vid;//视频id

        //数据详情
        $data = $this->init_thumbs($this->model->where('id = '.$id)->field('id,title,description,content,view,thumb,is_fee,price,video,artist')->find());
        // 一级栏目查询
        $model = $this->AuthRule->where("tables = '".$this->table."'")->find();
        // 艺人信息处理
        $res = $this->artist_show($data);
        // 视频解密处理
        // $res['video'] = $this->base64_de($res['video']);
        if($this->userid){
            $userid = $this->userid;//当前登录的用户
            // 判断视频是否收费或者用户是否为vip
            $userpay = $this->is_fee($id,$userid,$data['is_fee'],$data['price'],$model['price']);
            // 观看进度
            $res['percentage'] = $this->himatch($userid,$id,$model['tables']);
            // 是否收藏
            $res['is_collected'] = $this->collection($userid,$id,$model['tables']);
        }
        
        // 评论
        $comment = $this->comment($id,$model['tables'],$this->offset, $this->limit);
        // 点击量更新
        $data['view'] = $data['view'] + 1;
        $update['view'] = $data['view'];
        $this->model->where('id = '.$id)->update($update);
        $res['comment'] = $comment;
        // 排行榜
        // $rankings
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
        $data = $this->collectionsed($this->row,$this->table,$this->model,$models = 'match');
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
