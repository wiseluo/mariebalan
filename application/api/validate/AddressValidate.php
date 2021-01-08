<?php

namespace app\api\Validate;

use think\Validate;

class AddressValidate extends Validate
{
    protected $rule = [
        'province_id' => 'require|integer',
        'city_id' => 'require|integer',
        'town_id' => 'require|integer',
        'consignee' => 'require',
        'address' => 'require',
        'phone' => 'require|mobile',
        'isdefault' => 'require|in:0,1',
    ];
    
    protected $message = [
        'province_id.require' => '省必填',
        'province_id.integer' => '省id必须是数字',
        'city_id.require' => '市必填',
        'city_id.integer' => '市id必须是数字',
        'town_id.require' => '镇必填',
        'town_id.integer' => '镇id必须是数字',
        'consignee.require' => '收货人必填',
        'address.require' => '详细地址必填',
        'phone.require' => '手机号必填',
        'phone.mobile' => '手机号格式不正确',
        'isdefault.require' => '是否默认必填',
        'isdefault.in' => '是否默认错误',
    ];
    
    protected $scene = [
        'save' => ['province_id', 'city_id', 'town_id', 'consignee', 'address', 'phone', 'isdefault'],
        'update' => ['province_id', 'city_id', 'town_id', 'consignee', 'address', 'phone', 'isdefault'],
    ];
    
}

