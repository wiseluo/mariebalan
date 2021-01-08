<?php

namespace app\user\service;

class UserCouponService extends BaseService
{
    public function indexService($param)
    {
        $this->setUserCouponExpiry($param['user_id']);
        
        return $this->UserCouponRepository->userCoupons($param);
        // foreach($list as $k => $v) {
        //     $list[$k]['pic'] = common_func_pic_domain($v['pic']);
        // }
    }

    //设置过期
    public function setUserCouponExpiry($user_id)
    {
        $list = $this->UserCouponRepository->userCouponExpirylist($user_id);
        $time = time();
        foreach($list as $k => $v) {
            if(strtotime($v['expiry_edate'] .' 23:59:59') < $time) { //过期
                $this->UserCouponRepository->update(['state'=> 2], ['id'=> $v['id']]);
            }
        }

    }

    public function saveService($user_id, $coupon_id)
    {
        $coupon = model('admin/CouponRepository', 'repository')->get($coupon_id);
        $coupon_data = [
            'user_id' => $new_user_id,
            'coupon_id' => 1,
            'money' => $coupon['money'],
        ];
        if($type == 1) {
            $coupon_data['expiry_sdate'] = Date('Y-m-d');
            $coupon_data['expiry_edate'] = Date('Y-m-d', strtotime("+". $coupon['expiry_days'] ." day"));
        }else{
            $coupon_data['expiry_sdate'] = $coupon['expiry_sdate'];
            $coupon_data['expiry_edate'] = $coupon['expiry_edate'];
        }
        $user_coupon_id = model('user/UserCouponRepository', 'repository')->save($coupon_data);
        if($user_coupon_id) {
            return ['status'=> 1, 'msg'=> '添加用户优惠券成功'];
        }else{
            return ['status'=> 0, 'msg'=> '添加用户优惠券失败'];
        }
    }
}