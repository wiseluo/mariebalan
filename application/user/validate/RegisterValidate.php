<?php

namespace app\user\Validate;

use think\Validate;

class RegisterValidate extends Validate
{

    protected $rule = [
        'phone' => 'require|integer|mobile',
        'nickname' => 'require|max:50',
        'code' => 'require|integer|length:6',
        'password' => 'require|min:6',
        'agreement' => 'require|eq:1',
        'unionid' => 'require',
        'headimgurl' => 'require',
        'sex' => 'require|in:1,2',
    ];
    
    protected $message = [
        'phone.require' => '手机号必填',
        'phone.integer' => '手机号必须是数字',
        'phone.mobile' => '手机号格式不正确',
        'nickname.require' => '昵称必填',
        'nickname.max' => '昵称不能超过50位',
        'code.require' => '验证码必填',
        'code.integer' => '验证码必须是数字',
        'code.length' => '验证码长度错误',
        'password.require' => '密码必填',
        'password.min' => '密码最小6位',
        'agreement.require' => '请阅读并同意用户协议',
        'agreement.eq' => '用户协议错误',
        'unionid.require' => '微信授权码必填',
        'headimgurl.require' => '头像必填',
        'sex.require' => '性别必填',
    ];
    
    protected $scene = [
        'register' => ['phone', 'nickname', 'code', 'password', 'agreement'],
        'wechatRegister' => ['unionid', 'phone', 'code', 'password', 'nickname', 'headimgurl', 'sex'],
        'qqRegister' => ['unionid', 'phone', 'code', 'password', 'nickname', 'headimgurl', 'sex'],
    ];
    
}

