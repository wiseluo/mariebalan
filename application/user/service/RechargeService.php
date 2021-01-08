<?php

namespace app\user\service;

class RechargeService extends BaseService
{
    public function rechargeOrderService($pay_type, $money, $title)
    {
        $order_no = $this->RechargeOrderRepository->nextRechargeOrderNo();
        $user = request()->user();
        $data = [
            'state' => 1,
            'pay_type' => $pay_type,
            'user_id' => $user['id'],
            'order_no' => $order_no,
            'money' => $money,
            'title' => $title,
        ];
        $res = $this->RechargeOrderRepository->save($data);
        if($res) {
            return ['status'=> 1, 'msg'=> '充值下单成功', 'data'=> $order_no];
        }else{
            return ['status'=> 0, 'msg'=> '充值下单失败'];
        }
    }

}