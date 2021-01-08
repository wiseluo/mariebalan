<?php

namespace app\user\Validate;

use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'phone' => 'require|integer|mobile',
        'password' => 'require|min:6',
        'code' => 'require',
        'access_token' => 'require',
        'unionid' => 'require',
        'encryptedData' => 'require',
        'iv' => 'require',
    ];
    
    protected $message = [
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
        'phone.mobile' => '手机号格式不正确',
        'password.require' => '密码必填',
        'password.min' => '密码最小6位',
        'code.require' => '验证码必填',
        'access_token.require' => '用户access_token必填',
        'unionid.require' => '用户unionid必填',
        'encryptedData.require' => '加密数据必填',
        'iv.require' => '初始向量必填'
    ];
    
    protected $scene = [
        'login' => ['phone', 'password'],
        'wechatAppletLogin' => ['code', 'encryptedData', 'iv'],
        'wechatAppLogin' => ['unionid'],
        'qqLogin' => ['access_token'],
        'forgotPassword' => ['phone', 'code', 'password'],
    ];
    
}

