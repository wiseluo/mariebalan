<?php

namespace app\api\service;

class OrderCouponService extends BaseService
{
    public function saveService($order_id, $user_coupon_id)
    {
        $user_coupon = model('user/UserCouponRepository', 'repository')->get($user_coupon_id);
        $data = [
            'order_id' => $order_id,
            'user_id' => $user_coupon['user_id'],
            'user_coupon_id' => $user_coupon['user_coupon_id'],
            'money' => $user_coupon['money'],
        ];

        $coupon_res = $this->OrderCouponRepository->save($data);
        if($coupon_res) {
            $user_coupon_res = model('user/UserCouponRepository', 'repository')->update(['state'=> 1], ['id'=> $user_coupon_id]);
            if($user_coupon_res) {
                return ['status' => 1, 'msg' => '添加订单优惠券成功'];
            }else{
                return ['status' => 0, 'msg' => '修改用户优惠券使用情况失败'];
            }
        }else{
            return ['status' => 0, 'msg' => '添加订单优惠券失败'];
        }
    }

}