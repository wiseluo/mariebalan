<?php

namespace app\admin\Validate;

use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'username' => 'require|max:30',
        'password' => 'require|min:6',
    ];
    
    protected $message = [
        'username.require' => '用户名必填',
        'username.max' => '用户名最长30位',
        'password.require' => '密码必填',
        'password.min' => '密码最小6位',
    ];
    
    protected $scene = [
        'login' => ['username', 'password'],
    ];
    
}

