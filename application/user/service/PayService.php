<?php

namespace app\user\service;

use think\Db;

class PayService extends BaseService
{
    /**
      * 充值支付成功后的操作
      * data 支付返回的数据
    **/
    public function rechargePaySuccess($data)
    {
        //支付校验
        $order = model('user/RechargeOrderRepository', 'repository')->find(['order_no'=> $data['order_no']]);
        if($order == null) {
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
        $pay_data = [
            'type' => 1, //充值
            'pay_type' => $data['pay_type'],
            'user_id' => $order['user_id'],
            'order_id' => $order['id'],
            'order_no' => $data['order_no'],
            'trade_no' => $data['trade_no'],
            'money' => $data['money'],
        ];
        if ($order['state'] != 1) {
            $pay_data['state'] = 0;
            $pay_data['content'] = '订单状态异常-state:'. $order['state'];
            model('user/PayLogRepository', 'repository')->save($pay_data);
            return ['status'=> 0, 'msg'=> '订单状态异常'];
        }
        if ($order['money'] != $data['money']) {
            $pay_data['state'] = 0;
            $pay_data['content'] = '订单金额不正确-order_money:'. $order['money'] .' | 支付金额：'. $data['money'];
            model('user/PayLogRepository', 'repository')->save($pay_data);
            return ['status'=> 0, 'msg'=> '订单金额不正确'];
        }

        $pay_data['state'] = 1;
        $pay_data['content'] = '订单支付成功';
        model('user/PayLogRepository', 'repository')->save($pay_data);

        $order_res = model('user/RechargeOrderRepository', 'repository')->update(['state'=> 2], ['id'=> $order['id']]);

        //用户充值
        model('user/UserService', 'service')->userMoneyInc($order['user_id'], $data['money'], 1, '用户充值');
        //提升会员等级
        model('user/UserService', 'service')->upgradeVip($order['user_id'], $data['money']);
        //消费分销返现
        model('user/UserService', 'service')->payRebate($order['user_id'], $data['money']);
        return ['status'=> 1, 'msg'=> '订单支付成功'];
    }

    /**
      * 订单支付成功后的操作
      * data 支付返回的数据
    **/
    public function orderPaySuccess($data)
    {
        //支付校验
        $order = model('user/OrderRepository', 'repository')->find(['number'=> $data['order_no']]);
        if($order == null) {
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
        $pay_data = [
            'type' => 2, //下单
            'pay_type' => $data['pay_type'],
            'user_id' => $order['user_id'],
            'order_id' => $order['id'],
            'order_no' => $data['order_no'],
            'trade_no' => $data['trade_no'],
            'money' => $data['money'],
        ];
        if ($order['state'] != 1) {
            $pay_data['state'] = 0;
            $pay_data['content'] = '订单状态异常-state:'. $order['state'];
            model('user/PayLogRepository', 'repository')->save($pay_data);
            return ['status'=> 0, 'msg'=> '订单状态异常'];
        }
        if ($order['pay_money'] != $data['money']) {
            $pay_data['state'] = 0;
            $pay_data['content'] = '订单金额不正确-order_money:'. $order['pay_money'] .' | 支付金额：'. $data['money'];
            model('user/PayLogRepository', 'repository')->save($pay_data);
            return ['status'=> 0, 'msg'=> '订单金额不正确'];
        }
        $pay_data['state'] = 1;
        $pay_data['content'] = '订单支付成功';
        model('user/PayLogRepository', 'repository')->save($pay_data);

        $order_res = model('user/OrderRepository', 'repository')->update(['state'=> 2], ['id'=> $order['id']]);
        if(!$order_res) {
            return ['status'=> 0, 'msg'=> '订单修改失败'];
        }
        //增加销量 减库存(todo)
        $order_goods = model('api/OrderGoodsRepository', 'repository')->select(['order_id'=> $order['id']], 'id');
        foreach($order_goods as $k => $v) {
            model('api/GoodsRepository', 'repository')->salesInc($v['goods_id'], 1);
        }

        if($pay_type > 0) { //不是余额支付类型，提升会员等级，消费分销返现
            model('user/UserService', 'service')->upgradeVip($order['user_id'], $data['money']);
            model('user/UserService', 'service')->payRebate($order['user_id'], $data['money']);
        }
        return ['status'=> 1, 'msg'=> '订单支付成功'];
    }
}