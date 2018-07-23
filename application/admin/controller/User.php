<?php

namespace app\admin\controller;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\Db;

/**
 * 会员管理
 *
 * @icon user
 */
class User extends Backend
{
    
    /**
     * User模型对象
     */
    protected $model = null;

    /**
     * 搜索字段
     */
    protected $searchFields = 'id,username,mobile';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');

    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function index($role = 1){
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $status['role'] = $role; // 艺人
            $total = $this->model
                ->where($where)
                ->where($status)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->where($status)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    // 普通会员
    public function memindex($role = 0){
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $status['role'] = "0"; // 艺人
            $total = $this->model
                ->where($where)
                ->where($status)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->where($status)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch('memindex');
    }

    // 审核
    public function examine(){
        $ids = input('ids');
        if(input('posts')){
            if(input('posts') == '1'){
                $data['is_ok'] = input('ok1');
                $data['reason'] = input('reason1');
                $res = Db::table('fa_u_examine_basics')->where('userid = '.$ids)->update($data);
                $arr = array(
                    'status'    =>  '200',
                    'message'   =>  '提交成功',
                    );
            }
            if(input('posts') == '2'){
                $data['is_ok'] = input('ok2');
                $data['reason'] = input('reason2');
                $res = Db::table('fa_u_examine_photo')->where('userid = '.$ids)->update($data);
                $arr = array(
                    'status'    =>  '200',
                    'message'   =>  '提交成功',
                    );
            }
            if(input('posts') == '3'){
                $data['is_ok'] = input('ok3');
                $data['reason'] = input('reason3');
                $res = Db::table('fa_u_examine_video')->where('userid = '.$ids)->update($data);
                $arr = array(
                    'status'    =>  '200',
                    'message'   =>  '提交成功',
                    );
            }
            echo json_encode($arr);
        }else{
            $user_bascic = Db::table('fa_u_examine_basics')->where('userid = '.$ids)->find();
            $user_photo = Db::table('fa_u_examine_photo')->where('userid = '.$ids)->find();
            $user_video = Db::table('fa_u_examine_video')->where('userid = '.$ids)->find();
            $user_photo['thumb'] = unserialize($user_photo['thumb']);
            $len = strlen($user_video['video']);
            $user_video['video'] = substr($user_video['video'],5,$len);
            $user_video['video'] = base64_decode($user_video['video']);
            $user_video['video'] = base64_decode($user_video['video']);
            $this->view->assign("ids", $ids);
            $this->view->assign("basics", $user_bascic);
            $this->view->assign("photos", $user_photo);
            $this->view->assign("video", $user_video);
            // $array = array('/uploads/20180609/8b4534fd77d2b37b7facd3010a67dd17.gif','/uploads/20180609/8b4534fd77d2b37b7facd3010a67dd17.gif','/uploads/20180609/8b4534fd77d2b37b7facd3010a67dd17.gif');

            // echo "<pre>";
            // print_r($user_photo);
            // die;
            return $this->view->fetch('examine');
        }
        
    }


}
