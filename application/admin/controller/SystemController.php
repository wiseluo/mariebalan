<?php

namespace app\admin\controller;

use think\Request;
use app\admin\service\SystemService;
use app\admin\common\JwtTool;

class SystemController extends BaseController
{
    public function __construct(SystemService $systemService)
    {
        parent::__construct();
        $this->systemService = $systemService;
    }

    public function changePassword(Request $request)
    {
        $param = $request->param();
        $validate = validate('SystemValidate');
        if(!$validate->scene('changePassword')->check($param)) {
            return json(['code'=> 401, 'type'=> 'changePassword', 'msg'=> $validate->getError()]);
        }
        $param['admin_id'] = $request->admin()['id'];
        $list = $this->systemService->changePasswordService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function adminInfo(Request $request)
    {
        $admin_id = $request->admin()['id'];
        $res = $this->systemService->adminInfoService($admin_id);
        if($res) {
            return json(['code'=> 200, 'msg'=> '成功', 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->param('token');
        $jwtTool = new JwtTool();
        $jwtTool->deleteToken($token);
        return json(['code'=> 200, 'msg'=> '登出成功']);
    }
    
}
