<?php

namespace app\api\Validate;

use think\Validate;

class OrderValidate extends Validate
{
    protected $rule = [
        'pid' => 'require|integer',
        'num' => 'require|integer',
        'scids' => 'require',
        'address_id' => 'require|integer',
        'goods' => 'require|checkGoods',
        'user_coupon_id' => 'require|integer|checkUserCouponId',
    ];
    
    protected $message = [
        'pid.require' => '商品规格id必填',
        'pid.integer' => '商品规格id必须是数字',
        'num.require' => '商品数量必填',
        'num.integer' => '商品数量必须是数字',
        'scids.require' => '购物车商品id必填',
        'address_id.require' => '地址必填',
        'address_id.integer' => '地址id必须是数字',
        'goods.require' => '产品数据必填',
        'user_coupon_id.require' => '用户优惠券id必填',
        'user_coupon_id.integer' => '用户优惠券id必须是数字',
    ];
    
    protected $scene = [
        'orderNowPrejudge' => ['pid', 'num'],
        'orderNow' => ['pid', 'num'],
        'shopcartSettlementPrejudge' => ['scids'],
        'shopcartSettlement' => ['scids'],
        'save' => ['address_id', 'goods', 'user_coupon_id'],
    ];
    
    public function checkGoods($value, $rule, $data)
    {
        $goods = json_decode($value, true);
        if(count($goods) == 0) {
            $rule = '请填写产品信息';
        }
        array_walk($goods, function($item) use(&$rule, $data) {
            if(!isset($item['pid']) || !ctype_digit($item['pid'])) {
                $rule = '商品规格id必填并且为数字';
                return;
            }
            if(!isset($item['num']) || !ctype_digit($item['num'])) {
                $rule = '商品数量必填并且为数字';
                return;
            }
        });

        if ($rule) {
            return $rule;
        } else {
            return true;
        }
    }

    public function checkUserCouponId($value)
    {
        if($value > 0) {
            $user_coupon = model('user/UsersCouponRepository', 'repository')->get($value);
            if($user_coupon == null) {
                return '优惠券不存在';
            }else if(strtotime($user_coupon['expiry_time']) < time()) {
                return '优惠券已过期';
            }
        }
        return true;
    }
}

