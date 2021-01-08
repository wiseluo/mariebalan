<?php

namespace app\api\repository;

use app\api\model\OrderCoupon;

class OrderCouponRepository
{
    public function get($id)
    {
        return OrderCoupon::get($id);
    }

    public function find($where)
    {
        return OrderCoupon::where($where)->find();
    }

    public function select($where=[])
    {
        return OrderCoupon::where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $order_coupon = new OrderCoupon($data);
        $res = $order_coupon->allowField($field)->save();
        if($res){
            return $order_coupon->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $order_coupon = new OrderCoupon();
        return $order_coupon->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return OrderCoupon::destroy($where);
    }

    public function forceDelete($where)
    {
        return OrderCoupon::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $order_coupon = new OrderCoupon();
        return $order_coupon->restore($where);
    }

}
