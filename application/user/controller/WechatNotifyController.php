<?php

namespace app\user\controller;

use think\Request;
use think\Controller;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;

class WechatNotifyController extends Controller
{
    protected $pay_type = 1; //微信支付

    public function rechargeNotify(Request $request)
    {
        $pay = Pay::wechat(config('pay.wechat'));
        try{
            $data = $pay->verify(); // 是的，验签就这么简单！
            if($data && $data['result_code'] == 'SUCCESS') {
                //支付通知日志
                $notify_data = [
                    'type'=> 1, //充值
                    'pay_type'=> $this->pay_type,
                    'order_no' => $data['out_trade_no'],
                    'money' => bcdiv($data['total_fee'], 100, 2),
                    'content' => "收到来自微信的异步通知",
                ];
                model('user/PayNotifyRepository', 'repository')->save($notify_data);

                $pay_data = [
                    'pay_type' => $this->pay_type,
                    'order_no' => $data['out_trade_no'],
                    'trade_no' => $data['trade_no'],
                    'money' => bcdiv($data['total_fee'], 100, 2),
                ];
                $order_res = model('user/PayService', 'service')->rechargePaySuccess($pay_data);
                Log::debug('Wechat notify', $data->all());
                return $pay->success()->send();
            }
            $content = '订单交易状态失败:'. $data['err_code_des'];
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
        return $pay->success()->send();
    }

    public function orderNotify(Request $request)
    {
        $pay = Pay::wechat(config('pay.wechat'));
        try{
            $data = $pay->verify(); // 是的，验签就这么简单！
            if($data && $data['result_code'] == 'SUCCESS') {
                //支付通知日志
                $notify_data = [
                    'type'=> 2, //下单
                    'pay_type'=> $this->pay_type,
                    'order_no' => $data['out_trade_no'],
                    'money' => bcdiv($data['total_fee'], 100, 2),
                    'content' => "收到来自微信的异步通知",
                ];
                model('user/PayNotifyRepository', 'repository')->save($notify_data);

                $pay_data = [
                    'pay_type' => $this->pay_type,
                    'order_no' => $data['out_trade_no'],
                    'trade_no' => $data['trade_no'],
                    'money' => bcdiv($data['total_fee'], 100, 2),
                ];
                $order_res = model('user/PayService', 'service')->orderPaySuccess($pay_data);
                Log::debug('Wechat notify', $data->all());
                return $pay->success()->send();
            }
            $content = '订单交易状态失败:'. $data['err_code_des'];
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
        return $pay->success()->send();
    }

}
