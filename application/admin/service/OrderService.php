<?php

namespace app\admin\service;

class OrderService extends BaseService
{
    public function indexService($param)
    {
        $list = $this->OrderRepository->orderList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'state' => $value['state'],
                'state_str' => $value['state_str'],
                'user_id' => $value['user_id'],
                'nickname' => $value['nickname'],
                'number' => $value['number'],
                'money' => $value['money'],
                'pay_money' => $value['pay_money'],
                'create_time' => $value['create_time'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function readService($id)
    {
        $order = $this->OrderRepository->orderWithGoodsDetail($id);
        if($order) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $order];
        }else{
            return ['status' => 0, 'msg'=> '订单不存在'];
        }
    }

    public function toDeliverOrderListService($param)
    {
        $list = $this->OrderRepository->toDeliverOrderList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'nickname' => $value['nickname'],
                'number' => $value['number'],
                'money' => $value['money'],
                'pay_money' => $value['pay_money'],
                'create_time' => $value['create_time'],
                'consignee' => $value['consignee'],
                'province' => $value['province'],
                'city' => $value['city'],
                'town' => $value['town'],
                'address' => $value['address'],
                'phone' => $value['phone'],
                'remark' => $value['remark'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function deliverOrderService($param)
    {
        $order = $this->OrderRepository->get($param['order_id']);
        if($order) {
            if($order['state'] != 2) {
                return ['status' => 0, 'msg' => '该订单状态不能发货'];
            }
            $order_data = [
                'state' => 3,
                'express_name' => $param['express_name'],
                'express_no' => $param['express_no'],
            ];
            $order_res = $this->OrderRepository->update($order_data, ['id'=> $param['order_id']]);
            if($order_res) {
                return ['status'=> 1, 'msg'=> '发货成功'];
            }else{
                return ['status' => 0, 'msg' => '发货失败'];
            }
        }else{
            return ['status' => 0, 'msg' => '订单不存在'];
        }
    }

    public function deleteService($id)
    {
        $order = $this->OrderRepository->get($id);
        if($order) {
            $res = $this->OrderRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
    }

}