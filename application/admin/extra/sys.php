<?php
/**
 * Created by PhpStorm.
 * User: wangcailin
 * Date: 2017/10/30
 * Time: 上午11:22
 */

if (!function_exists('category_del_papers')) {

    /**
     * 科目考卷处理函数
     * @param $data
     * @return mixed
     */
    function category_del_papers($data)
    {
        foreach ($data as $k=>$v){
            if (!$v['papers']){
                unset($data[$k]);
            }
        }
        return $data;
    }

}
