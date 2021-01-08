<?php

namespace app\user\service;

use app\common\common\JwtTool;
use think\Db;

class RegisterService extends BaseService
{
    public function registerService($param)
    {
        $user = $this->UserRepository->find([['phone', '=', $param['phone']]]);
        if($user) {
            return ['status'=> 0, 'msg'=> '手机号码已存在，不能重复注册'];
        }
        $sms = model('user/SmsService', 'service')->verifySms($param['phone'], $param['code']);
        if($sms['status'] == 0) {
            return ['status'=> 0, 'msg'=> $sms['msg']];
        }
        $referee_id = (isset($param['referee']) && $param['referee'] != '') ? $param['referee'] : null;
        $user_data = [
            'phone' => $param['phone'],
            'password' => md5($param['password']),
            'nickname' => $param['nickname'],
            'referee_id' => $referee_id, //推荐人
        ];
        $user_id = $this->UserRepository->save($user_data);
        if($user_id) {
            if($referee_id) {
                $distribution_res = model('user/UserService', 'service')->dealNewUser($referee_id, $user_id);
            }
            return ['status'=> 1, 'msg'=> '注册成功'];
        }else{
            return ['status'=> 0, 'msg'=> '注册失败'];
        }
    }

    //type 1微信登录账号 2qq登录账号
    public function otherRegisterService($param, $type)
    {
        $sms = model('user/SmsService', 'service')->verifySms($param['phone'], $param['code']);
        if($sms['status'] == 0) {
            return ['status'=> 0, 'msg'=> $sms['msg']];
        }
        $user = $this->UserRepository->find([['phone', '=', $param['phone']]]);
        if($user) { //手机号已存在，账号覆盖
            $update_data = [
                'password' => md5($param['password']),
                'nickname'=> $param['nickname'],
                'headimgurl'=> $param['headimgurl'],
                'sex'=> $param['sex'],
            ];
            if($type == 1) {
                $update_data['unionid_wx'] = $param['unionid'];
            }else if($type == 2) {
                $update_data['unionid_qq'] = $param['unionid'];
            }
            $user_res = $this->UserRepository->update($update_data, ['id'=> $user['id']]);
            if($user_res) {
                $user = $this->UserRepository->get($user_id);
                $jwtTool = new JwtTool();
                $token = $jwtTool->setAccessToken($user);
                return ['status'=> 1, 'msg'=> '登录成功', 'data'=>['token'=> $token]];
            }else{
                return ['status'=> 0, 'msg'=> '手机号码绑定失败'];
            }
        }

        $referee_id = (isset($param['referee']) && $param['referee'] != '') ? $param['referee'] : null;
        $save_data = [
            'phone' => $param['phone'],
            'password' => md5($param['password']),
            'nickname'=> $param['nickname'],
            'headimgurl'=> $param['headimgurl'],
            'sex'=> $param['sex'],
            'referee_id' => $referee_id, //推荐人
        ];
        if($type == 1) {
            $save_data['unionid_wx'] = $param['unionid'];
        }else if($type == 2) {
            $save_data['unionid_qq'] = $param['unionid'];
        }
        $user_id = $this->UserRepository->save($save_data);
        if($user_id) {
            if($referee_id) {
                $distribution_res = model('user/UserService', 'service')->dealNewUser($referee_id, $user_id);
            }
            $user = $this->UserRepository->get($user_id);
            $jwtTool = new JwtTool();
            $token = $jwtTool->setAccessToken($user);
            return ['status'=> 1, 'msg'=> '登录成功', 'data'=>['token'=> $token]];
        }else{
            return ['status'=> 0, 'msg'=> '绑定失败'];
        }
    }

}