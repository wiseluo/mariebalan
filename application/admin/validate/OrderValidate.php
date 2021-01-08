<?php

namespace app\admin\Validate;

use think\Validate;

class OrderValidate extends Validate
{
    protected $rule = [
        'order_id' => 'require|integer',
        'express_name' => 'require',
        'express_no' => 'require',
    ];
    
    protected $message = [
        'order_id.require' => '订单id必填',
        'order_id.integer' => '订单id必须是数字',
        'express_name.require' => '快递公司必填',
        'express_no.require' => '快递单号必填',
    ];
    
    protected $scene = [
        'deliverOrder' => ['order_id', 'express_name', 'express_no'],
    ];
    
}

