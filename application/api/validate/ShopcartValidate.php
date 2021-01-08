<?php

namespace app\api\Validate;

use think\Validate;

class ShopcartValidate extends Validate
{
    protected $rule = [
        'pid' => 'require|integer',
        'num' => 'require|integer',
        'ids' => 'require|checkIds',
    ];
    
    protected $message = [
        'pid.require' => '商品规格id必填',
        'pid.integer' => '商品规格id必须是数字',
        'num.require' => '商品数量必填',
        'num.integer' => '商品数量必须是数字',
        'ids.require' => '购物车商品id必填',
    ];
    
    protected $scene = [
        'save' => ['pid', 'num'],
        'update' => ['pid', 'num'],
        'batchDelete' => ['ids'],
    ];
    
    public function checkIds($value)
    {
        $idsarr = explode(',', $value);
        foreach($idsarr as $k => $v) {
            if(!is_numeric($v)) {
                $rule = '购物车商品id必须是数字';
                return;
            }
            $shopcart = model('api/ShopcartRepository', 'repository')->get($v);
            if($shopcart == null) {
                return '购物车商品不存在';
            }
        }
        return true;
    }
}

