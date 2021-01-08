<?php

namespace app\user\service;

class AccountLogService extends BaseService
{
    public function accountLogService($type, $style, $user_id, $change_money, $user_money, $title)
    {
        $data = [
            'type' => $type,
            'style' => $style,
            'user_id' => $user_id,
            'change_money' => $change_money,
            'user_money' => $user_money,
            'title' => $title,
        ];
        $res = $this->AccountLogRepository->save($data);
        if($res) {
            return ['status'=> 1, 'msg'=> '用户账户日志成功'];
        }else{
            return ['status'=> 0, 'msg'=> '用户账户日志失败'];
        }
    }

}