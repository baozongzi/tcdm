<?php
namespace app\common\validate;

use think\Validate;
use think\Db;

class Cart extends Validate
{
    // 验证规则
    protected $rule = [
        'user_note' => 'max:50',
    ];
    //错误信息
    protected $message = [
        'user_note.max' => '留言长度最多为50个字符'
    ];

}