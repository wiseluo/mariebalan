<?php

namespace app\user\service;

use Yansongda\Pay\Pay;

class AlipayService extends BaseService
{
    public function rechargePayService($param)
    {
        $recharge_res = model('user/RechargeService', 'service')->rechargeOrderService(2, $param['money'], '支付宝充值');
        if($recharge_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> $recharge_res['msg']];
        }
        $order = [
            'out_trade_no' => $recharge_res['data'], // 订单号
            'total_amount' => $param['money'], // 订单金额，单位元
            'subject' => '支付宝充值', // 订单描述
        ];
        $config = config('pay.alipay'); //获取配置参数
        $config['notify_url'] = common_func_domain() .'/alipay/recharge_notify'; //加入回调url
        $result = Pay::alipay($config)->app($order);
        $res = $result->getContent();
        return ['status'=> 1, 'data'=> $res, 'msg'=> '成功'];
    }

    public function orderPayService($param)
    {
        $order = $this->OrderRepository->get($param['order_id']);
        if (!$order || $order['state'] != 1) {
            return ['status'=> 0, 'msg'=> '订单状态异常'];
        }
        if((strtotime($order['create_time']) + 2400) < time()) { //超40分钟，失效
            $this->OrderRepository->update(['state'=> 5], ['id'=> $param['order_id']]);
            return ['status'=> 0, 'msg'=> '订单已失效，请重新下单'];
        }

        $order = [
            'out_trade_no' => $order['number'], // 订单号
            'total_amount' => $order['pay_money'], // 付款金额，单位元
            'subject' => $order['content'], // 订单描述
        ];
        $config = config('pay.alipay'); //获取配置参数
        $config['notify_url'] = common_func_domain() .'/alipay/order_notify'; //加入回调url
        $result = Pay::alipay($config)->app($order);
        $res = $result->getContent();
        // $res = "app_id=2021001170608021&format=JSON&charset=utf-8&sign_type=RSA2&version=1.0&notify_url=http%3A%2F%2Fmariebalan.jk-kj.com%2Falipay%2Forder_notify&timestamp=2020-07-03+09%3A43%3A09&biz_content=%7B%22out_trade_no%22%3A%22202007020943356008%22%2C%22total_amount%22%3A%220.00%22%2C%22subject%22%3A%2218K%5Cu91d1%5Cu94bb%5Cu77f3%5Cu84dd%5Cu5b9d%5Cu77f3%5Cu6212%5Cu6307+x1+%22%2C%22product_code%22%3A%22QUICK_MSECURITY_PAY%22%7D&method=alipay.trade.app.pay&sign=Hz%2FfVccQig6Sro57j0MbMZ1kS4%2BzFojDLDdFtQA8PuUXhgZ4eTMjSw8z72%2BtKimLacuR%2BtdyoSFaaxZEYPcnFL8%2FdpYFloBjA8dzi5FddHp%2BdD46fPzj3Vqd6gPIsycqJAaIB3%2FfoyjkoSd0Cgr3N5uFSninehGvmPNCOYQDAihRqGuZ2XBkuU08Ps%2Fgb1GumGmhK72oAaiRiLJXlGCKAEec7kFwRwI%2BXHvHzFNQMfAVm9noCXDl%2F3i3gWPgniPtadmGROZ92NhCMPbMLZTXXxFOL%2Bg1iD08LvwMfAL%2FOyFmISiq0Bor%2FoDdMuV2U%2FWg1wP32G1giN20VkQSXZzIFw%3D%3D"
        return ['status'=> 1, 'data'=> $res, 'msg'=> '成功'];
    }
}