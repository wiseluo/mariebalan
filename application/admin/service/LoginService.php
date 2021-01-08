<?php

namespace app\admin\service;

use app\admin\common\JwtTool;

class LoginService extends BaseService
{
    public function loginService($param)
    {
        $admin = $this->AdminRepository->find([['username', '=', $param['username']]]);
        if($admin == null) {
            return ['status'=> 0, 'msg'=> '用户名不正确'];
        }
        if($admin['password'] != md5($param['password'])) {
            return ['status'=> 0, 'msg'=> '密码不正确'];
        }
        $jwtTool = new JwtTool();
        $token = $jwtTool->setAccessToken($admin);
        return ['status'=> 1, 'msg'=> '登录成功', 'data'=>['token'=> $token]];
    }
    
}