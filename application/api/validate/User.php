<?php

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'moblie' => 'require|unique:moblie|length:11',
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['mobile'],
        'edit' => [],
    ];
    
}
