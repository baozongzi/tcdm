<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Controller;
use fast\Tree;
use think\Cache;
use think\Request;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Publics extends Backend
{
    
    /**
     * Publics模型对象
     */
    public function _initialize()
    {
        parent::_initialize();
    }

    public function search_crowd(){
        // 继承backerd.php中的方法
        $data = $this->crowd_search();
        exit(json_encode($data));
    }

}