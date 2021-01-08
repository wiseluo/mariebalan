<?php

namespace app\admin\repository;

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

    public function userList($param)
    {
        if($param['keyword']) {
            $where[] = ['UserCoupon.nickname', 'like', '%'. $param['keyword'] .'%'];
        }

        return UserCoupon::alias('UserCoupon')
            ->join('yw_vip Vip', 'Vip.id=UserCoupon.vip_id', 'left')
            ->field('UserCoupon.*,Vip.name vip_name')
            ->where($where)
            ->order('UserCoupon.id', 'desc')
            ->paginate($param['page_size'])
            ->toArray();
    }

}
