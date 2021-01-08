<?php

namespace app\user\controller;

use think\Request;
use app\user\service\AlipayService;

class AlipayController extends BaseController
{
    public function __construct(AlipayService $alipayService)
    {
        parent::__construct();
        $this->alipayService = $alipayService;
    }

    //充值
    public function rechargePay(Request $request)
    {
        $param = $request->param();
        $validate = validate('AlipayValidate');
        if(!$validate->scene('rechargePay')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->alipayService->rechargePayService($param);
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
        $validate = validate('AlipayValidate');
        if(!$validate->scene('orderPay')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->alipayService->orderPayService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
}
