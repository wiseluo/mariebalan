<?php

namespace app\admin\repository;

use app\admin\model\Coupon;

class CouponRepository
{
    public function get($id)
    {
        return Coupon::get($id);
    }

    public function find($where)
    {
        return Coupon::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return Coupon::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $coupon = new Coupon($data);
        $res = $coupon->allowField($field)->save();
        if($res){
            return $coupon->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $coupon = new Coupon();
        return $coupon->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Coupon::destroy($where);
    }

    public function forceDelete($where)
    {
        return Coupon::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $coupon = new Coupon();
        return $coupon->restore($where);
    }

    public function couponList($param)
    {
        $where = [];
        if($param['keyword']) {
            $where[] = ['name', 'like', '%'. $param['keyword'] .'%'];
        }

        return Coupon::where($where)
            ->field('id,name,money,expiry_days,expiry_sdate,expiry_edate,describe')
            ->order('id', 'desc')
            ->paginate($param['page_size'])
            ->toArray();

    }
}
