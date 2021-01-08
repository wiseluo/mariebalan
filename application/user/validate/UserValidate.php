<?php

namespace app\user\Validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'order_id' => 'require|integer',
        'headimgurl' => 'require',
    ];
    
    protected $message = [
        'order_id.require' => '订单号必填',
        'order_id.integer' => '订单id必须是数字',
        'headimgurl.require' => '头像地址必填',
    ];
    
    protected $scene = [
        'orderAccountPay' => ['order_id'],
        'updateHeadimgurl' => ['headimgurl'],
    ];
    
}

