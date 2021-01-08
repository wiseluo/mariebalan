<?php

namespace app\admin\controller;

use think\Request;
use think\Controller;
use app\admin\service\LoginService;

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
    
}
