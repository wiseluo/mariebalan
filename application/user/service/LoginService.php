<?php

namespace app\user\service;

use app\common\common\JwtTool;
use app\user\common\WechatHelper;
use app\user\common\QqHelper;
use app\user\common\WechatAppletHelper;

class LoginService extends BaseService
{
    public function loginService($param)
    {
        $user = $this->UserRepository->find([['phone', '=', $param['phone']]]);
        if($user == null) {
            return ['status'=> 0, 'msg'=> '手机号不正确'];
        }
        if($user['password'] != md5($param['password'])) {
            return ['status'=> 0, 'msg'=> '密码不正确'];
        }
        $jwtTool = new JwtTool();
        $token = $jwtTool->setAccessToken($user);
        return ['status'=> 1, 'msg'=> '登录成功', 'data'=>['token'=> $token]];
    }

    public function wechatAppletLoginService($param)
    {
        $wxApplethelper = new WechatAppletHelper();
        $userinfo_res = $wxApplethelper->decryptData($param['code'], $param['encryptedData'], $param['iv']);
        if($userinfo_res['status']) {
            $referee = (isset($param['referee']) && $param['referee'] != '') ? $param['referee'] : 0;
            $user = $this->UserRepository->find([['unionid_wx', '=', $userinfo_res['data']['unionid']]]);
            if($user) {
                $jwtTool = new JwtTool();
                $token = $jwtTool->setAccessToken($user);
                return ['status'=> 1, 'msg'=> '登录成功', 'data'=> ['token'=> $token, 'referee'=> $referee]];
            }else{
                return ['status'=> 1, 'msg'=> '请绑定手机号', 'data'=> ['token'=> '', 'unionid'=> $userinfo_res['data']['unionid'], 'referee'=> $referee]];
            }
        }else{
            return ['status'=> 0, 'msg'=> $userinfo_res['msg']];
        }
    }

    public function wechatAppLoginService($param)
    {
        $referee = (isset($param['referee']) && $param['referee'] != '') ? $param['referee'] : 0;
        $user = $this->UserRepository->find([['unionid_wx', '=', $param['unionid']]]);
        if($user) {
            $jwtTool = new JwtTool();
            $token = $jwtTool->setAccessToken($user);
            return ['status'=> 1, 'msg'=> '登录成功', 'data'=> ['token'=> $token, 'referee'=> $referee]];
        }else{
            return ['status'=> 1, 'msg'=> '请绑定手机号', 'data'=> ['token'=> '', 'unionid'=> $param['unionid'], 'referee'=> $referee]];
        }
    }

    public function qqLoginService($param)
    {
        $qqhelper = new QqHelper();
        $qq_res = $qqhelper->getOauth($param['access_token']);
        if($qq_res['status']) {
            $referee = (isset($param['referee']) && $param['referee'] != '') ? $param['referee'] : 0;
            $user = $this->UserRepository->find([['unionid_qq', '=', $qq_res['data']['unionid']]]);
            if($user) {
                $jwtTool = new JwtTool();
                $token = $jwtTool->setAccessToken($user);
                return ['status'=> 1, 'msg'=> '登录成功', 'data'=> ['token'=> $token, 'referee'=> $referee]];
            }else{
                return ['status'=> 1, 'msg'=> '请绑定手机号', 'data'=> ['token'=> '', 'unionid'=> $qq_res['data']['unionid'], 'referee'=> $referee]];
            }
        }else{
            return ['status'=> 0, 'msg'=> $qq_res['msg']];
        }
    }
    
    public function forgotPasswordService($param)
    {
        $user = $this->UserRepository->find(['phone'=> $param['phone']]);
        if($user == null) {
            return ['status'=> 0, 'msg'=> '手机号码不存在'];
        }
        if(request()->user()['id'] != $user['id']) {
            return ['status'=> 0, 'msg'=> '手机号与该账号绑定的手机号不一致'];
        }
        $sms = model('user/SmsService', 'service')->verifySms($param['phone']);
        if($sms['status'] == 0) {
            return ['status'=> 0, 'msg'=> $sms['msg']];
        }
        $user_data = [
            'password' => md5($param['password']),
        ];
        $res = $this->UserRepository->update($user_data, ['id'=> $user['id']]);
        if($res) {
            return ['status'=> 1, 'msg'=> '密码重置成功'];
        }else{
            return ['status'=> 0, 'msg'=> '密码重置失败'];
        }
    }
}