<?php

namespace app\admin\Validate;

use think\Validate;

class CouponValidate extends Validate
{
    protected $rule = [
        'name' => 'require|max:50',
        'money' => 'require|checkMoney',
    ];
    
    protected $message = [
        'name.require' => '名称必填',
        'money.require' => '金额必填',
    ];
    
    protected $scene = [
        'save' => ['name', 'money'],
        'update' => ['name', 'money'],
    ];
    
    public function checkMoney($value, $rule, $data)
    {
        if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $value)) {
            return '请输入正确的金额';
        } else {
            return true;
        }
    }
    
}

