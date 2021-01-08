<?php

namespace app\user\controller;

use think\Request;
use think\Controller;
use app\user\service\LoginService;

class LoginController extends Controller
{
    public function __construct(LoginService $loginService)
    {
        parent::__construct();
        $this->loginService = $loginService;
    }

    public function login(Request $request)
    {
        $param = $request->param();
        $validate = validate('LoginValidate');
        if(!$validate->scene('login')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->loginService->loginService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function wechatAppletLogin(Request $request)
    {
        $param = $request->param();
        $validate = validate('LoginValidate');
        if(!$validate->scene('wechatAppletLogin')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->loginService->wechatAppletLoginService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function wechatAppLogin(Request $request)
    {
        $param = $request->param();
        $validate = validate('LoginValidate');
        if(!$validate->scene('wechatAppLogin')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->loginService->wechatAppLoginService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function qqLogin(Request $request)
    {
        $param = $request->param();
        $validate = validate('LoginValidate');
        if(!$validate->scene('qqLogin')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->loginService->qqLoginService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
    public function forgotPassword(Request $request)
    {
        $param = $request->param();
        $validate = validate('LoginValidate');
        if(!$validate->scene('forgotPassword')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->loginService->forgotPasswordService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
}
