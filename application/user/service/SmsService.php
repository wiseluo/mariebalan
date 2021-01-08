<?php

namespace app\user\service;

use app\user\common\SmsHelper;

class SmsService extends BaseService
{
    public function smsCodeService($phone)
    {
        $sms = $this->SmsRepository->find([['phone', '=', $phone]]);
        if($sms) {
            if(time() - strtotime($sms['create_time']) < 300) {
                return ['status'=> 0, 'msg'=> '每5分钟可获取一次验证码，请稍候再来。'];
            }
        }
        // 随机6位数
        $code = rand(100000, 999999);
        //变量模板ID
        $template = '380228';
        $content = "【商翔】您好，您的验证码是：". $code ."，有效期为5分钟。如非本人操作，请忽略此短信。";
        $res = SmsHelper::sendSMS('', '', $phone, $content, $template);
        $res = json_decode($res);
        if ($res->code == 0) {
            $sms_data = [
                'phone' => $phone,
                'code' => $code,
                'content' => $content,
            ];
            $sms_res = $this->SmsRepository->save($sms_data);
            if($sms_res) {
                return ['status'=> 1, 'msg'=> '短信发送成功'];
            }else{
                return ['status'=> 0, 'msg'=> '短信保存失败'];
            }
        } else {
            return ['status'=> 0, 'msg'=> '短信发送失败! 状态：' . $res->message];
        }
    }

    public function verifySms($phone, $code)
    {
        $sms = $this->SmsRepository->getLastOne([['phone', '=', $phone]]);
        if($sms == null) {
            return ['status'=> 0, 'msg'=> '验证码不存在'];
        }else if(time() - strtotime($sms['create_time']) > 300) {
            return ['status'=> 0, 'msg'=> '验证码已过期，请重新获取'];
        }else if(!hash_equals($sms['code'], $code)) { //可防止时序攻击的字符串比较
            return ['status'=> 0, 'msg'=> '验证码错误'];
        }
        return ['status'=> 1, 'msg'=> 'sms验证成功'];
    }
}