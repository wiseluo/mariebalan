<?php

namespace app\user\common;

use Curl\Curl;

class QqHelper
{
    private $appid = '101884107'; //应用唯一标识
    private $app_key = '95abab98132f3fd6183c110e77343732';

    public function getQqUserinfo($access_token, $openid)
    {
        $userinfo_res = $this->getUserinfoQqApi($access_token, $openid);
        if($userinfo_res['status'] == 1) {
            $userinfo = [
                'openid'=> $openid,
                'unionid'=> $userinfo_res['data']['unionid'],
                'sex'=> ($userinfo_res['data']['gender'] == '女') ? 2 : 1,
                'nickname'=> $userinfo_res['data']['nickname'],
                'headimgurl'=> $userinfo_res['data']['figureurl_qq_1'],
            ];
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $userinfo];
        }else{
            return ['status'=> 0, 'msg'=> $userinfo_res['msg']];
        }
    }

    public function getUserinfoQqApi($access_token, $openid)
    {
        $url = 'https://graph.qq.com/user/get_user_info?access_token='. $access_token .'&oauth_consumer_key='. $this->appid .'&openid='.$openid;
        $curl = new Curl();
        $curl->get($url);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> '获取userinfo接口失败'];
        }else{
            $result = json_decode($curl->response, true);
        }
        if(isset($result['ret']) && $result['ret'] == 0) {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result];
        }else{
            return ['status'=> 0, 'msg'=> '获取userinfo接口失败-'. $result['msg']];
        }
    }

    /**
      * 通过access_token获取unionid
      * param access_token
      * return client_id openid unionid
    **/
    public function getOauth($access_token)
    {
        $url = 'https://graph.qq.com/oauth2.0/me?access_token='. $access_token .'&unionid=1&fmt=json';
        $curl = new Curl();
        $curl->get($url);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> '获取qq接口失败'];
        }else{
            $result = json_decode($curl->response, true);
        }
        if(isset($result['error'])) {
            return ['status'=> 0, 'msg'=> '获取qq接口失败-'. $result['error_description']];
        }else{
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result];
        }
    }

}