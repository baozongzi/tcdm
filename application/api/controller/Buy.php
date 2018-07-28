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

class buy extends Api
{

    /**
     * Teacher模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        // $this->model = model('Story');
        // $this->catname = 'Story';
        // $this->table = 'story';
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
        $this->offset = ($this->page - 1) * 2;
        $this->limit  = $this->page * 2;
        $this->website = model('Config')->where('name', 'website')->value('value');
        // $method = $this->ispost();
        // if($method == POST){
        //     $err['id'] = '0';
        //     $json_arr = array('status'=>1,'msg'=>'请按套路出牌😏','data'=>$err );
        //     $json_str = json_encode($json_arr);
        //     exit($json_str);
        // }

    }
    //投票说明
    public function ballot(){
        $ballot = Db::table('fa_ballot')->where('status = 1')->select();
        $result['ballot'] = $ballot;
        return $this->json_echo('1','获取成功😏',$result);
    }
    // 钻石说明
    public function diamond(){
        $diamond = Db::table('fa_diamond')->where('status = 1')->select();
        $result['diamond'] = $diamond;
        return $this->json_echo('1','获取成功😏',$result);
    }

    // 我要打赏
    public function paydiamond(){
        $userid = $this->row->userid;
        $user = Db::table('fa_user')->where('id = '.$userid)->field('id,normal_name,head,diamond')->find();
        $ranking = Db::table('fa_user')->where('diamond > '.$user['diamond'])->count();
        $overone = Db::table('fa_user')->field('diamond')->where('diamond > '.$user['diamond'])->order('id desc')->limit(1)->find();
        $user['distance'] = $overone['diamond'] - $user['diamond'];
        $user['ranking'] = $ranking + 1;
        $data['ranking'] = $user['ranking'];
        $user['head'] = $this->website.$user['head'];
        Db::table('fa_user')->where('id = '.$userid)->update($data);
        $user['fans'] = Db::table('fa_fans')->where('artistid = '.$userid)->count();
        return $this->json_echo('1','获取成功😏',$user);
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
        $data = $this->collectionsed($this->row,$this->table,$this->model,$models = 'story');
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
