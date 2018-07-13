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
        //成语故事
        $story = $this->init_thumbs(Db::table('fa_story')->where('status = 1 AND is_index = 1')->field('id,title,thumb,view')->order('updatetime desc,id desc')->limit(5)->select());
        //健康养生
        $health1 = $this->init_thumbs(Db::table('fa_health_interview')->where('status = 1 AND is_index = 1')->field('id,title,thumb,inputtime')->order('updatetime desc,id desc')->limit(1)->find());
        $health2 = $this->init_thumbs(Db::table('fa_health_story')->where('status = 1 AND is_index = 1')->field('id,title,thumb,inputtime')->order('updatetime desc,id desc')->limit(1)->find());
        $health = array($health1,$health2);
        //城市文化
        $city = $this->init_thumbs(Db::table('fa_city')->where('status = 1 AND is_index = 1')->field('id,title,thumb,inputtime')->order('updatetime desc,id desc')->limit(2)->select());
        //最新综艺
        $variety = $this->init_thumbs(Db::table('fa_variety')->where('status = 1 AND is_index = 1')->field('id,title,thumb,inputtime')->order('updatetime desc,id desc')->limit(2)->select());
        //艺人包装
        $match = $this->init_thumbs(Db::table('fa_match')->where('status = 1 AND is_index = 1')->field('id,title,thumb,inputtime')->order('updatetime desc,id desc')->limit(2)->select());

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

    
    

}
