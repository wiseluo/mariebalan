<?php

namespace app\admin\controller;

use think\facade\Request;
use think\Controller;
use app\admin\common\JwtTool;

class BaseController extends Controller
{
    public function __construct()
    {
        $request = request();
        $token = $request->param('token', '', 'trim') == '' ? $request->header('x-token') : $request->param('token', '', 'trim');
        if($token == '') {
            echo json_encode(['code' => 201, 'data' => '', 'msg'=>'无效令牌']);
            exit;
        }
        $jwtTool = new JwtTool();
        $jwt = $jwtTool->validateToken($token);
        if($jwt) {
            $admin = model('admin/AdminRepository', 'repository')->get($jwt->id);
            if($admin == null) {
                echo json_encode(['code' => 201, 'data' => '', 'msg'=>'登录失效,请重新登录！']);
                exit;
            }
            //admin方法注入到请求头中
            $request->hook('admin', function() use($admin) {
                return $admin;
            });
        }else{
            echo json_encode(['code' => 201, 'data' => '', 'msg'=>'无效令牌']);
            exit;
        }
    }
}
