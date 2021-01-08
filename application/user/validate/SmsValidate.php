<?php

namespace app\user\Validate;

use think\Validate;

class SmsValidate extends Validate
{
    protected $rule = [
        'phone' => 'require|integer|mobile',
    ];
    
    protected $message = [
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
        'phone.mobile' => '手机号格式不正确',
    ];
    
    protected $scene = [
        'smsCode' => ['phone'],
    ];
    
}

