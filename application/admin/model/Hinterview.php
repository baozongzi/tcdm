<?php

namespace app\admin\model;

use think\Model;

class Hinterview extends Model
{
	protected $name = 'health_interview';
	
	// 判断数据表
	public function seltables($cid){
		switch ($cid) {
        case '1':
            return $tables = 'fa_health_interview';
            break;
        case '2':
            return $tables = 'fa_health_story';
            break;
        case '3':
            return $tables = 'fa_health_product';
            break;
        case '4':
            return $tables = 'fa_health_common';
            break;
        }
	}


    


}
