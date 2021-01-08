<?php

namespace app\user\controller;

use think\Request;
use think\Controller;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;

class AlipayNotifyController extends Controller
{
    protected $pay_type = 2; //支付宝支付

    public function rechargeNotify(Request $request)
    {
        $pay = Pay::wechat(config('pay.wechat'));
        try{
            $data = $pay->verify(); // 是的，验签就这么简单！
            if($data) { // 验证成功
                $trade_status = $data->trade_status;// 交易状态
                if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                    //支付成功后的业务逻辑处理
                    //支付通知日志
                    $notify_data = [
                        'type'=> 1, //充值
                        'pay_type'=> $this->pay_type,
                        'order_no' => $data['out_trade_no'],
                        'money' => $data['total_amount'],
                        'content' => "收到来自支付宝的异步通知",
                    ];
                    model('user/PayNotifyRepository', 'repository')->save($notify_data);

                    $pay_data = [
                        'pay_type' => $this->pay_type,
                        'order_no' => $data['out_trade_no'],
                        'trade_no' => $data['trade_no'],
                        'money' => $data['total_amount'],
                    ];
                    $order_res = model('user/PayService', 'service')->rechargePaySuccess($pay_data);
                    Log::debug('Alipay notify', $data->all());
                    return $alipay->success()->send();
                }
            }
            $content = '订单交易状态失败';
        } catch (\Exception $e) {
            $content = $e->getMessage();
        }
        $notify_data = [
            'type' => 1, //充值
            'pay_type'=> $this->pay_type,
            'order_no' => '',
            'money' => '',
            'content' => $content,
        ];
        model('user/PayNotifyRepository', 'repository')->save($notify_data);
        return $alipay->success()->send();
    }

    public function orderNotify(Request $request)
    {
        $alipay = Pay::alipay(config('pay.alipay'));
        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            if($data) { // 验证成功
                $trade_status = $data->trade_status;// 交易状态
                if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                    //支付成功后的业务逻辑处理
                    //支付通知日志
                    $notify_data = [
                        'type'=> 2, //下单
                        'pay_type'=> $this->pay_type,
                        'order_no' => $data['out_trade_no'],
                        'money' => $data['total_amount'],
                        'content' => "收到来自支付宝的异步通知",
                    ];
                    model('user/PayNotifyRepository', 'repository')->save($notify_data);

                    $pay_data = [
                        'pay_type' => $this->pay_type,
                        'order_no' => $data['out_trade_no'],
                        'trade_no' => $data['trade_no'],
                        'money' => $data['total_amount'],
                    ];
                    $order_res = model('user/PayService', 'service')->orderPaySuccess($pay_data);
                    Log::debug('Alipay notify', $data->all());
                    return $alipay->success()->send();
                }
            }
            $content = '订单交易状态失败';
        } catch (\Exception $e) {
            $content = $e->getMessage();
        }
        $notify_data = [
            'type' => 2,
            'pay_type'=> $this->pay_type,
            'order_no' => '',
            'money' => '',
            'content' => $content,
        ];
        model('user/PayNotifyRepository', 'repository')->save($notify_data);
        return $alipay->success()->send();
    }

}
