<?php

namespace app\api\validate;

use think\Validate;

class ActivityUser extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'uid' => 'require',
        'aid' => 'require'
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'uid.require' => '用户id不存在',
        'aid.require' => '活动id不存在',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => [],
        'edit' => [],
    ];

}
