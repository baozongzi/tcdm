<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class Table extends Controller
{
    /**
     * 显示资源列表
     * @return \think\Response
     */
    public function index()
    {
        $config     = [
            // 数据库类型
            'type'            => 'mysql',
            // 服务器地址
            'hostname'        => '118.31.47.137',
            // 数据库名
            'database'        => 'fireadmin',
            // 用户名
            'username'        => 'root',
            // 密码
            'password'        => 'root',
            // 端口
            'hostport'        => '',
            // 连接dsn
            'dsn'             => '',
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8
            'charset'         => 'utf8',
        ];
        $connect    = Db::connect($config);

        $dbname     = 'fireadmin';

        // 查询所有表列表
        $sql        = "SELECT table_name as name ,table_comment as comment FROM information_schema.tables  WHERE table_schema = '$dbname'";
        $table_list = $connect->query($sql);

        $table_temp_list    = $table_list;


        $output = "";

        $output .= htmlentities('<a name="返回顶部"></a>',ENT_QUOTES,"UTF-8")."<br/>";

        foreach ($table_list as $k => $v)
        {
            $table_temp = "* [{name}({comments})](#{name})";
            $table_temp     = str_replace('{name}',$v['name'],$table_temp);
            $comment        = !empty($v['comment']) ? $v['comment'] : $v['name'];
            $table_temp     = str_replace('{comments}',$comment,$table_temp);

            $output .= $table_temp."</br>";

            $table_list[$k]['ul'] = $table_temp;
        }

        foreach ($table_list as $k => $v)
        {
            //遍历处理字段列表
            $md_header = "| Field    | Type | Collation| Null | Key | Default | Extra| Privileges|Comment|"."<br/>"."
|:---------| :----| :------  | :----| :---| :-------| :----| :---------| :-----|"."<br/>";

            $name           = $v['name'];
            $sql        = "SHOW FULL COLUMNS FROM $name";
            $fields     = $connect->query($sql);

            $output .= htmlentities('<a name="'.$v['name'].'"></a>',ENT_QUOTES,"UTF-8")."<br/>";
            $output .=  "* ".$v['name']."(".$v['comment'].")[↑](#返回顶部)"."<br/><p></p>";
            foreach ($fields as $kk => $vv){
                $field_temp_row     = "| {Field} | {Type} | {Collation} | {Null}| {Key}| {Default} | {Extra} | {Privileges} | {Comment} |";
                $field_temp_row = str_replace("{Field}",$vv['Field'],$field_temp_row);
                $field_temp_row = str_replace("{Type}",$vv['Type'],$field_temp_row);
                $field_temp_row = str_replace("{Collation}",$vv['Collation'],$field_temp_row);
                $field_temp_row = str_replace("{Null}",$vv['Null'],$field_temp_row);
                $field_temp_row = str_replace("{Key}",$vv['Key'],$field_temp_row);
                $field_temp_row = str_replace("{Default}",$vv['Default'],$field_temp_row);
                $field_temp_row = str_replace("{Extra}",$vv['Extra'],$field_temp_row);
                $field_temp_row = str_replace("{Privileges}",$vv['Privileges'],$field_temp_row);

                $comment        = !empty($vv['Comment']) ? $vv['Comment'] : "未注释";
                $field_temp_row = str_replace("{Comment}",$comment,$field_temp_row);

                $md_header = $md_header .$field_temp_row."<br/>";
            }
            $output .=  $md_header."<br/><br/>";
            $table_list[$k]['fields_md'] = $md_header;
        }
        echo $output ;
    }
}
