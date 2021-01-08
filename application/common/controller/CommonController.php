<?php

namespace app\common\controller;

use think\Db;
use think\Request;
use think\Controller;
use app\common\common\JwtTool;

class CommonController extends Controller
{
    public function __construct()
    {
        $request = request();
        $token = $request->param('token', '', 'trim');
        if($token == '') {
            echo json_encode(['code' => 201, 'data' => '', 'msg'=>'请先登录']);
            exit;
        }
        $jwtTool = new JwtTool();
        $jwt = $jwtTool->validateToken($token);
        if($jwt) {
            $user = model('user/UserRepository', 'repository')->get($jwt->id);
            if($user == null) {
                echo json_encode(['code' => 201, 'data' => '', 'msg'=>'登录失效,请重新登录！']);
                exit;
            }
            //user方法注入到请求头中
            $request->hook('user', function () use($user){
                return $user;
            });
        }else{
            echo json_encode(['code' => 201, 'data' => '', 'msg'=>'请先登录']);
            exit;
        }
    }

}
