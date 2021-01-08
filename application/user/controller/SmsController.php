<?php

namespace app\user\controller;

use think\Request;
use think\Controller;
use app\user\service\SmsService;

class SmsController extends Controller
{
    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    public function smsCode(Request $request)
    {
        $param = $request->param();
        $validate = validate('SmsValidate');
        if(!$validate->scene('smsCode')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->smsService->smsCodeService($param['phone']);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
}
