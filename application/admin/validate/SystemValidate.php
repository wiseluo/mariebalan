<?php

namespace app\admin\Validate;

use think\Validate;

class SystemValidate extends Validate
{
    protected $rule = [
        'old_password' => 'require|min:6',
        'password' => 'require|min:6|confirm',
    ];
    
    protected $message = [
        'old_password.require' => '旧密码必填',
        'old_password.min' => '旧密码最小6位',
        'password.require' => '新密码必填',
        'password.min' => '新密码最小6位',
        'password.confirm' => '两次输入密码不一致',
    ];
    
    protected $scene = [
        'changePassword' => ['old_password', 'password'],
    ];
    
}

