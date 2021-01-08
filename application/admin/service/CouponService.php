<?php

namespace app\admin\service;

class CouponService extends BaseService
{
    public function indexService($param)
    {
        return $this->CouponRepository->couponList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'state' => $value['state'],
                'title' => $value['title'],
                'pic' => $value['pic'],
                'url' => $value['url'],
                'sort' => $value['sort'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function readService($id)
    {
        $coupon = $this->CouponRepository->get($id);
        if($coupon){
            return ['status' => 1, 'msg'=> '成功', 'data'=> $coupon];
        }else{
            return ['status' => 0, 'msg'=> '优惠券不存在'];
        }
    }

    public function saveService($param)
    {
        $coupon_data = [
            'state' => $param['state'],
            'title' => $param['title'],
            'pic' => $param['pic'],
            'url' => $param['url'],
            'sort' => $param['sort'],
        ];
        $coupon_id = $this->CouponRepository->save($coupon_data);
        if($coupon_id) {
            return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $coupon_id];
        }else{
            return ['status' => 0, 'msg' => '添加失败'];
        }
    }

    public function updateService($param, $id)
    {
        $coupon_data = [
            'state' => $param['state'],
            'title' => $param['title'],
            'pic' => $param['pic'],
            'url' => $param['url'],
            'sort' => $param['sort'],
        ];
        $coupon_res = $this->CouponRepository->update($coupon_data, ['id'=> $id]);
        if($coupon_res) {
            return ['status'=> 1, 'msg'=> '修改成功', 'data'=> $coupon_res];
        }else{
            return ['status' => 0, 'msg' => '修改失败'];
        }
    }

    public function deleteService($id)
    {
        $coupon = $this->CouponRepository->get($id);
        if($coupon) {
            $res = $this->CouponRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '优惠券不存在'];
        }
    }

}