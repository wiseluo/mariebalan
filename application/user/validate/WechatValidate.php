<?php

namespace app\user\Validate;

use think\Validate;

class WechatValidate extends Validate
{
    protected $rule = [
        'money' => 'require|checkMoney',
        'order_id' => 'require|integer',
    ];
    
    protected $message = [
        'money.require' => '金额必填',
        'order_id.require' => '订单id必填',
        'order_id.integer' => '订单id必须是数字',
    ];
    
    protected $scene = [
        'rechargePay' => ['money'],
        'orderPay' => ['order_id'],
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

