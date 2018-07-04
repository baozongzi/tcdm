<?php
/**
 * Created by PhpStorm.
 * User: wangcailin
 * Date: 2017/10/30
 * Time: 上午11:22
 */
if (!function_exists('api_json')) {
    /**
     * 自定义api接口转换函数 以COOKIE中access_token 中角色区分校验拦截
     * @param $error_code
     * @param $message
     * @param $data
     * @param int $http_code
     * @return \think\response\Json
     */
    function api_json($error_code,$message,$data,$http_code = 200)
    {
        // 进行token解码校验
        $access_token   = cookie("access_token");
        if(empty($access_token) && isset($access_token)){
            $data    = array(
                'success' => false,
                'code'  => 0,
                'msg' => 'token不能为空',
                'data'    => array(),
            );
            return json($data);
        }
        if($access_token)
        {
            $arr = array(
                'mobile'    =>  $data['mobile'],
                'unique'    =>  $data['unique'],
                'time'      =>  date("Y-m-d H",time())
            );
            $base64 = base64_encode(json_encode($arr));
            $hmac = hash_hmac('sha256', $base64, Api_Key, $as_binary=false);
            echo "<pre>";
            print_r($hmac);
            echo "<br>";
            echo $access_token;
            die;
            // $decoded = JWT::decode($access_token, Token::$key, array('HS256'));
            // 校验是非后台访问,进行数据格式转换处理
            // 账户类型: 1 ,mobile;2,email,3,admin
            if($access_token == $hmac)
            {
                $data    = array(
                    'success' => $error_code > 0 ? false : true,
                    'code'  => $error_code > 0 ? $error_code : 0,
                    'msg' => $message,
                    'data'    => $data,
                );
            }else{
                $data    = array(
                    'success' => false,
                    'code'  => 0,
                    'msg' => '嘿嘿,不要找事儿啊,你还没有权限',
                    'data'    => array(),
                );
            }
        }else
        {
            // 无令牌访问信息
            $data    = array(
                'success' => $error_code > 0 ? false : true,
                'code'  => $error_code > 0 ? $error_code : 0,
                'msg' => $message,
                'data'    => $data
            );
        }
        return json($data,$http_code);
    }
}

// if (!function_exists('api_json')) {
//     /**
//      * 自定义api接口转换函数 以COOKIE中access_token 中角色区分校验拦截
//      * @param $error_code
//      * @param $message
//      * @param $data
//      * @param int $http_code
//      * @return \think\response\Json
//      */
//     function api_json($error_code,$message,$data,$http_code = 200)
//     {
//         // 进行token解码校验
//         $access_token   = cookie("access_token");
//         if($access_token)
//         {
//             $decoded = JWT::decode($access_token, Token::$key, array('HS256'));
//             // 校验是非后台访问,进行数据格式转换处理
//             // 账户类型: 1 ,mobile;2,email,3,admin
//             if($decoded->type != 3)
//             {
//                 $data    = [
//                     'success' => $error_code > 0 ? false : true,
//                     'code'  => $error_code > 0 ? $error_code : 0,
//                     'msg' => $message,
//                     'data'    => $data,
//                 ];
//             }
//         }else
//         {
//             // 无令牌访问信息
//             $data    = [
//                 'success' => $error_code > 0 ? false : true,
//                 'code'  => $error_code > 0 ? $error_code : 0,
//                 'msg' => $message,
//                 'data'    => $data
//             ];
//         }
//         return json($data,$http_code);
//     }
// }

if (!function_exists('api_json_url')) {

    /**
     * 转换静态资源url
     * @param $data 二维数组
     * @param $key 键
     */
    function api_json_url($data, $key)
    {
        foreach ($data as $k=>&$v){
            $v[$key] = 'http://fire.mcykj.com'.$v[$key];
        }
        return $data;
    }

}

if (!function_exists('create_code')) {
    /**
     * 生成短信验证码
     *  - 默认长度 6
     * @param int $length
     * @return string
     */
    function create_code($length = 6) {
        return str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('my_scandir')) {
    /**
     * 获取文件目录及其下的所有文件
     */
    function my_scandir($dir){
        $files=array();
        if(is_dir($dir)){
            if($handle = opendir($dir)){
                $i = 0;
                while(($file = readdir($handle))!==false){
                    if($file != '.'  && $file != ".."){
                        if(is_dir($dir."/".$file)){
                            $files[$file] = my_scandir($dir."/".$file);
                        }else{
                            $files[$i]['url']  = $dir."/".$file;
                            $files[$i]['name'] = $file;
                            $files[$i]['time'] = date('Y-m-d',filemtime($dir."/".$file));
                        }
                    }
                    $i++;
                }
            }
        }
        closedir($handle);
        return $files;
    }
}
