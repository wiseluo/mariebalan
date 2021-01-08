<?php

namespace app\user\controller;

use think\Request;
use app\user\service\WechatService;

class WechatController extends BaseController
{
    public function __construct(WechatService $wechatService)
    {
        parent::__construct();
        $this->wechatService = $wechatService;
    }

    //充值
    public function rechargePay(Request $request)
    {
        $param = $request->param();
        $validate = validate('WechatValidate');
        if(!$validate->scene('rechargePay')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->wechatService->rechargePayService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    //订单支付
    public function orderPay(Request $request)
    {
        $param = $request->param();
        $validate = validate('WechatValidate');
        if(!$validate->scene('orderPay')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->wechatService->orderPayService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
}
