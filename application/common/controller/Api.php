<?php

namespace app\common\controller;

use think\Controller;
use think\Lang;
use think\Config;
use think\Hook;
use think\Session;
use think\Request;
use think\Db;

class Api extends Controller
{

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = [];

    /**
     * 布局模板
     * @var string
     */
    protected $layout = 'default';

    /**
     * 权限控制类
     * @var Auth
     */
    protected $auth = null;

    /**
     * 快速搜索时执行查找的字段
     */
    protected $searchFields = 'id';

    /**
     * 是否是关联查询
     */
    protected $relationSearch = false;

    /**
     * 是否开启数据限制
     * 支持auth/personal
     * 表示按权限判断/仅限个人
     * 默认为禁用,若启用请务必保证表中存在admin_id字段
     */
    protected $dataLimit = false;

    /**
     * 数据限制字段
     */
    protected $dataLimitField = 'admin_id';

    /**
     * 是否开启Validate验证
     */
    protected $modelValidate = false;

    /**
     * 是否开启模型场景验证
     */
    protected $modelSceneValidate = false;

    /**
     * Multi方法可批量修改的字段
     */
    protected $multiFields = 'status';

    public function _initialize()
    {
        $modulename = $this->request->module();
        $controllername = strtolower($this->request->controller());
        $actionname = strtolower($this->request->action());

        $path = str_replace('.', '/', $controllername) . '/' . $actionname;

        // 定义是否Addtabs请求
        !defined('IS_ADDTABS') && define('IS_ADDTABS', input("addtabs") ? TRUE : FALSE);

        // 定义是否Dialog请求
        !defined('IS_DIALOG') && define('IS_DIALOG', input("dialog") ? TRUE : FALSE);

        // 定义是否AJAX请求
        !defined('IS_AJAX') && define('IS_AJAX', $this->request->isAjax());

        // 非选项卡时重定向
        if (!$this->request->isPost() && !IS_AJAX && !IS_ADDTABS && !IS_DIALOG && input("ref") == 'addtabs')
        {
            $url = preg_replace_callback("/([\?|&]+)ref=addtabs(&?)/i", function($matches) {
                return $matches[2] == '&' ? $matches[1] : '';
            }, $this->request->url());
            $this->redirect('index/index', [], 302, ['referer' => $url]);
            exit;
        }

        $this->website = model('Config')->where('name', 'website')->value('value');

        // 语言检测
        $lang = strip_tags(Lang::detect());

        $site = Config::get("site");

        $upload = \app\common\model\Config::upload();

        // 上传信息配置后
        Hook::listen("upload_config_init", $upload);

        // 配置信息
        $config = [
            'site'           => array_intersect_key($site, array_flip(['name', 'cdnurl', 'version', 'timezone', 'languages'])),
            'upload'         => $upload,
            'modulename'     => $modulename,
            'controllername' => $controllername,
            'actionname'     => $actionname,
            'jsname'         => 'backend/' . str_replace('.', '/', $controllername),
            'moduleurl'      => rtrim(url("/{$modulename}", '', false), '/'),
            'language'       => $lang,
            'fastadmin'      => Config::get('fastadmin'),
            'referer'        => Session::get("referer")
        ];
        // 配置信息后
        Hook::listen("config_init", $config);
        //加载当前控制器语言包
        $this->loadlang($controllername);
        //渲染站点配置
        $this->assign('site', $site);
        //渲染配置信息
        $this->assign('config', $config);
        //渲染权限对象
        $this->assign('auth', $this->auth);
        //渲染管理员对象
        $this->assign('admin', Session::get('admin'));
    }

    /**
     * 加载语言文件
     * @param string $name
     */
    protected function loadlang($name)
    {
        Lang::load(APP_PATH . $this->request->module() . '/lang/' . Lang::detect() . '/' . str_replace('.', '/', $name) . '.php');
    }

    /**
     * 渲染配置信息
     * @param mixed $name 键名或数组
     * @param mixed $value 值
     */
    protected function assignconfig($name, $value = '')
    {
        $this->view->config = array_merge($this->view->config ? $this->view->config : [], is_array($name) ? $name : [$name => $value]);
    }


    /**
     * Selectpage的实现方法
     *
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     *
     */
    protected function selectpage()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'htmlspecialchars']);

        //搜索关键词,客户端输入以空格分开,这里接收为数组
        $word = (array) $this->request->request("q_word/a");
        //当前页
        $page = $this->request->request("page");
        //分页大小
        $pagesize = $this->request->request("per_page");
        //搜索条件
        $andor = $this->request->request("and_or");
        //排序方式
        $orderby = (array) $this->request->request("order_by/a");
        //显示的字段
        $field = $this->request->request("field");
        //主键
        $primarykey = $this->request->request("pkey_name");
        //主键值
        $primaryvalue = $this->request->request("pkey_value");
        //搜索字段
        $searchfield = (array) $this->request->request("search_field/a");
        //自定义搜索条件
        $custom = (array) $this->request->request("custom/a");
        $order = [];
        foreach ($orderby as $k => $v)
        {
            $order[$v[0]] = $v[1];
        }
        $field = $field ? $field : 'name';

        //如果有primaryvalue,说明当前是初始化传值
        if ($primaryvalue !== null)
        {
            $where = [$primarykey => ['in', $primaryvalue]];
        }
        else
        {
            $where = function($query) use($word, $andor, $field, $searchfield, $custom) {
                foreach ($word as $k => $v)
                {
                    foreach ($searchfield as $m => $n)
                    {
                        $query->where($n, "like", "%{$v}%", $andor);
                    }
                }
                if ($custom && is_array($custom))
                {
                    foreach ($custom as $k => $v)
                    {
                        $query->where($k, '=', $v);
                    }
                }
            };
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds))
        {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        $list = [];
        $total = $this->model->where($where)->count();
        if ($total > 0)
        {
            if (is_array($adminIds))
            {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($where)
                ->order($order)
                ->page($page, $pagesize)
                ->field("{$primarykey},{$field}")
                ->field("password,salt", true)
                ->select();
        }
        //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
        return json(['list' => $list, 'total' => $total]);
    }
    // 生成token
    public function create_token($data){
        $arr = array(
                'mobile'    =>  $data['mobile'],
                'password'      =>  $data['password'],
                'unique'    =>  $data['unique'],
                'time'      =>  date("Y-m-d H",time())
            );
        $base64 = base64_encode(json_encode($arr));
        $hmac = hash_hmac('sha256', $base64, Api_Key, $as_binary=false);
        return $hmac;
    }
    // 验证token
    public function rule($token,$userid){
        $user = Db::table("fa_user")->field('access_token')->where('id = '.$userid)->find();
        $access_token = $user['access_token'];
        if($access_token == $token){

        }else{
            $err['id'] = '0';
            $json_arr = array('status'=>1,'msg'=>'请按套路出牌😏😏','data'=>$err );
            $json_str = json_encode($json_arr);
            exit($json_str);
        }
    }
    // json化输出
    public function json_echo($status,$mes,$result){
        $json_arr = array('status'=>$status,'msg'=>$mes,'data'=>$result );
        $json_str = json_encode($json_arr);
        return $json_str;
    }
    
    // 判断是否是post
    public function ispost(){
        $request = Request::instance();
        $method = $request->method();//获取上传方式
        $request->param();//获取所有参数，最全
        $get = $request->get();//获取get上传的内容
        $post = $request->post();//获取post上传的内容
        $file = $request->file('file');//获取文件
        $data = array();
        if($post){
            $data = $post;
            $data['count'] = count($post);
        }else if($get){
            $data = $post;
            $data['count'] = count($get);
        }else{
            $data['count'] = '0';
        }
        return $data;
    }

    // 视频是否收费判断
     public function is_fee($id,$userid,$fee,$d_price,$m_price){
        // 详情数据不收费
        if($fee == '0'){
            $userpay = '1';
        }
        // 详情收费，价格未设置，按照一级栏目收费
        if($fee == '1' && $d_price == '0'){
            $userpay = $this->userpay($id,$userid);
        }
        // 详情收费,价格已设定
        if($fee == '1'){
            $userpay = $this->userpay($id,$userid);
        }
        return $userpay;
    }
    // 查看用户是否购买视频
    public function userpay($id,$userid){
        $time = strtotime(date("Y-m-d",time()));
        //判断是否为vip
        $userpay = Db::table('fa_user_vip')->where("userid = ".$userid." AND createtime <='".$time."' AND endtime >= '".$time."'")->find();
        if(!$userpay){
            //判断视频是否已购买
            $userpay = Db::table('fa_userpay')->where("vid = ".$id." AND userid = ".$userid." AND model = '".$this->catname."'")->find();
        }
        if(empty($userpay)){
            return false;
        }
        return $userpay;
    }
    
    // 前台艺人信息读取
    public function artist_show($row){
        // 判断是否选择了艺人
        if($row['artist']){
            $row['artist'] = unserialize($row['artist']);
            foreach ($row['artist'] as $r => $v) {
                unset($row['artist']);
                // $row['artists'][$r]['userid'] = $v;
                $user = Db::table("fa_user")->field('id,head,normal_name,diamond')->where('id = '.$v['userid'])->find();
                $artists[$r]['userid'] = $user['id'];
                $artists[$r]['head'] = $this->website.$user['head'];
                $artists[$r]['normal_name'] = $user['normal_name'];
                $artists[$r]['cosplay'] = $v['cosplay'];
                $artists[$r]['diamond'] = $user['diamond'];
            }
        }else{
            $artists = array();
        }
        // 按照钻石数量排序
        $diamond = array();
        foreach ($artists as $artist) {
          $diamond[] = $artist['diamond'];
        }
        array_multisort($diamond, SORT_DESC, $artists);
        $row['artist'] = $artists;
        return $row;
    }

    //观看历史
    public function history($userid,$id,$tablename){
        $history = Db::table('fa_history')->where('userid = '.$userid." AND vid = ".$id." AND tables = '".$tablename."'")->find();
        if(!$history){
            $history['percentage'] = "0";
        }
        return $history['percentage'];
    }
    //是否收藏
    public function collection($userid,$id,$tablename){
        $collection = Db::table('fa_collection')->where('userid = '.$userid." AND vid = ".$id." AND tables = '".$tablename."'")->find();
        if($collection){
            $is_collected = '1';
        }else{
            $is_collected = '0';
        }
        return $is_collected;
    }
    //评论
    public function comment($userid,$id,$tablename,$offset, $limit){
        $count = Db::table('fa_'.$tablename.'_comment')->where("vid = ".$id)->count();
        $comment = Db::table('fa_'.$tablename.'_comment')->where("vid = ".$id)->order('inputtime desc')->limit($offset, $limit)->select();
        foreach ($comment as $com => $value) {
            $comment[$com]['head'] = $this->website.$comment[$com]['head'];
        }
        return $comment;
    }

    // 自定义数据输出
    public function encode($arr,$message){
        $json_arr = array('status'=>1,'msg'=>$message,'result'=>$arr );
        $json_str = json_encode($json_arr);
        exit($json_str);
    }

    // 时间初始化
    public function init_time($result){
        foreach ($result as $r => $res) {
            if($result[$r]['inputtime'] == null){
                $result[$r]['inputtime'] = '';
            }
        }
        return $result;
    }

    // // url解密
    // function base64_de($str){
    //     $len = strlen($str);
    //     $str = substr($str,5,$len);
    //     $str = base64_decode($str);
    //     $str = base64_decode($str);
    //     return $str;
    // }
    
    // 初始化图片
    public function init_thumbs($result){
        foreach ($result as $r => $res) {
            $result[$r]['thumb'] = explode(',',$result[$r]['thumb']);
            if(count($result[$r]['thumb']) > 1){
                foreach ($result[$r]['thumb'] as $t => $tb) {
                    $http = explode('://',$result[$r]['thumb'][$t]);
                    if($http[0] !== 'http' && $http[0] !== 'https'){
                        $result[$r]['thumb'][$t] = $this->website.$tb;
                    }else{
                        $result[$r]['thumb'][$t] = $tb;
                    }
                    if($http[0] == 'https'){
                        $result[$r]['thumb'][$t] = $tb;
                    }
                }
            }else{
                if($result[$r]['thumb']){
                    $exps = $result[$r]['thumb'];
                    $exp = $exps[0];
                }else{
                    $exp = $res['thumb'][0];
                }
                $http = explode('://',$exp);
                if($http[0] !== 'http' && $http[0] !== 'https'){
                    $result[$r]['thumb'] = $this->website.$exp;
                }else{
                    $result[$r]['thumb'] = $exp;
                }
                if($http[0] == 'https'){
                    $result[$r]['thumb'] = $res['thumb'];
                }
            }
        }
        if(isset($result['thumb'])){
            $https = explode('://',$result['thumb']);
            $http = explode('://',$result['thumb']);
            if($http[0] !== 'http' || $https[0] !== 'https'){
                $result['thumb'] = $this->website.$result['thumb'];
            }
        }
        return $result;
    }
}
