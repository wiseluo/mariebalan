<?php

namespace app\user\controller;

use think\Request;
use think\Controller;
use app\user\service\RegisterService;

class RegisterController extends Controller
{
    public function __construct(RegisterService $registerService)
    {
        parent::__construct();
        $this->registerService = $registerService;
    }

    public function register(Request $request)
    {
        $data = $request->param();
        $validate = validate('RegisterValidate');
        if(!$validate->scene('register')->check($data)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->registerService->registerService($data);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function wechatRegister(Request $request)
    {
        $data = $request->param();
        $validate = validate('RegisterValidate');
        if(!$validate->scene('wechatRegister')->check($data)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->registerService->otherRegisterService($data, 1);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function qqRegister(Request $request)
    {
        $data = $request->param();
        $validate = validate('RegisterValidate');
        if(!$validate->scene('qqRegister')->check($data)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->registerService->otherRegisterService($data, 2);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
}
