<?php

namespace app\user\repository;

use app\user\model\UserCoupon;

class UserCouponRepository
{
    public function get($id)
    {
        return UserCoupon::get($id);
    }

    public function find($where)
    {
        return UserCoupon::where($where)->find();
    }

    public function save($data, $field = true) {
        $user_coupon = new UserCoupon($data);
        $res = $user_coupon->allowField($field)->save();
        if($res){
            return $user_coupon->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $user_coupon = new UserCoupon();
        return $user_coupon->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return UserCoupon::destroy($where);
    }

    public function forceDelete($where)
    {
        return UserCoupon::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $user_coupon = new UserCoupon();
        return $user_coupon->restore($where);
    }

    public function userCouponExpirylist($user_id)
    {
        return UserCoupon::field('id,expiry_edate')
            ->where('user_id', '=', $user_id)
            ->where('state', '=', 0)
            ->select()
            ->toArray();
    }

    public function userCoupons($param)
    {
        $where[] = ['UserCoupon.user_id', '=', $param['user_id']];
        $where[] = ['UserCoupon.state', '=', $param['state']];

        return UserCoupon::alias('UserCoupon')
            ->join('yw_coupon Coupon', 'Coupon.id=UserCoupon.coupon_id', 'left')
            ->field('UserCoupon.id,UserCoupon.state,Coupon.name,Coupon.describe,UserCoupon.money,UserCoupon.expiry_sdate,UserCoupon.expiry_edate')
            ->where($where)
            ->select()
            ->toArray();
    }

    public function userCouponsCount($user_id)
    {
        return UserCoupon::field('count(id) coupons_count')
            ->where('user_id', '=', $user_id)
            ->select();
    }

}
