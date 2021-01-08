<?php

namespace app\user\service;

use Yansongda\Pay\Pay;

class WechatService extends BaseService
{
    public function rechargePayService($param)
    {
        $recharge_res = model('user/RechargeService', 'service')->rechargeOrderService(1, $param['money'], '微信充值');
        if($recharge_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $recharge_res['msg']];
        }
        $order = [
            'out_trade_no' => $recharge_res['data'], // 订单号
            'total_fee' => $param['money'] *100, // 订单金额，**单位：分**
            'body' => '微信充值', // 订单描述
        ];
        $config = config('pay.wechat'); //获取配置参数
        $config['notify_url'] = common_func_domain() .'/wechat/recharge_notify'; //加入回调url
        $result = Pay::wechat($config)->app($order);
        $json = $result->getContent();
        $res = json_decode($json);
        return ['status'=> 1, 'data'=> $res, 'msg'=> '成功'];
    }

    public function orderPayService($param)
    {
        $order = $this->OrderRepository->get($param['order_id']);
        if (!$order || $order->state != 1) {
            return ['status'=> 0, 'msg'=> '订单状态异常'];
        }
        if((strtotime($order['create_time']) + 2400) < time()) { //超40分钟，失效
            $this->OrderRepository->update(['state'=> 5], ['id'=> $param['order_id']]);
            return ['status'=> 0, 'msg'=> '订单已失效，请重新下单'];
        }

        $order = [
            'out_trade_no' => $order['number'], // 订单号
            'total_fee' => $order['pay_money'] *100, // 付款金额，**单位：分**
            'body' => $order['content'], // 订单描述
        ];
        $config = config('pay.wechat'); //获取配置参数
        $config['notify_url'] = common_func_domain() .'/wechat/order_notify'; //加入回调url
        $result = Pay::wechat($config)->app($order);
        $json = $result->getContent();
        $res = json_decode($json);
        // $res = {
        //     "appid": "wx732a1766b872e34a",
        //     "partnerid": "1600311942",
        //     "prepayid": "wx03093833549731537adb75d51451794500",
        //     "timestamp": "1593740313",
        //     "noncestr": "j1GMT2TUcI3XlYMy",
        //     "package": "Sign=WXPay",
        //     "sign": "39D4E0EBB88F4BDAE767122D365947EC"
        // }
        return ['status'=> 1, 'data'=> $res, 'msg'=> '成功'];
    }
}