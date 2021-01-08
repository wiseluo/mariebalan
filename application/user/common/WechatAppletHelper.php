<?php

namespace app\user\common;

use Curl\Curl;
use app\user\common\WxBizDataCrypt;

class WechatAppletHelper
{
    private $appid = ''; //小程序唯一标识
    private $app_secret = ''; //小程序密钥

    public function __construct()
    {
        $this->appid = config('wechat.applet_app_id');
        $this->app_secret = config('wechat.applet_app_secret');
    }

    /**
      * return jsonObj: openid session_key unionid
    **/
    public function getJscode2sessionWxApi($code)
    {
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='. $this->appid .'&secret='. $this->app_secret .'&js_code='. $code .'&grant_type=authorization_code';
        $curl = new Curl();
        $curl->get($url);
        if($curl->error) {
            return ['status'=> 0, 'msg'=> '微信获取授权失败'];
        }else{
            $result = json_decode($curl->response, true);
        }
        if(isset($result['errcode'])) {
            return ['status'=> 0, 'msg'=> '微信获取授权失败-'. $result['errmsg']];
        }else{
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $result];
        }
    }

    //加密数据解密算法
    //（ 后端判断是否有 unionid ）前端在调用 wx.getUserInfo() 时候带着登录态，然后不管后台能不能拿到 unionid，都把 encryptedData 和 iv 返回给后端，后端在拿到前端 code 之后去请求微信的接口拿 unionid，如果返回的 unionid 为空，再拿前端传的 encryptedData、iv以及之前的 session_key 解密出 unionid
    public function decryptData($code, $encryptedData, $iv)
    {
        $jscode2session = $this->getJscode2sessionWxApi($code);
        if($jscode2session['status'] == 0) {
            return ['status'=> 0, 'msg'=> $jscode2session['msg']];
        }
        if(isset($jscode2session['data']['unionid']) && $jscode2session['data']['unionid'] != '') {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> ['unionid'=> $jscode2session['data']['unionid']]];
        }
        $session_key = $jscode2session['data']['session_key'];
        $pc = new WxBizDataCrypt($this->appid, $session_key);
        $err_code = $pc->decryptData($encryptedData, $iv, $data);
        if($err_code == 0) {
            $data_arr = json_decode($data, true);
            return ['status'=> 1, 'msg'=> '成功', 'data'=> ['unionid'=> $data_arr['unionid']]];
        } else {
            return ['status'=> 0, 'msg'=> '解密数据失败-'. $err_code];
        }
    }
}