<?php

namespace app\user\service;

class IncomeLogService extends BaseService
{
    public function incomeLogService($type, $style, $user_id, $change_income, $user_income, $title)
    {
        $referee = $this->UserRepository->get($user_id);
        $incomelog_data = [
            'type' => $type,
            'style' => $style,
            'user_id' => $user_id,
            'vip_id' => $referee['vip_id'],
            'change_income' => $change_income,
            'user_income' => $user_income,
            'title' => $title,
        ];
        $income_log_res = $this->IncomeLogRepository->save($incomelog_data);
        if($income_log_res) {
            return ['status' => 1, 'msg' => '收益日志成功'];
        }else{
            return ['status' => 0, 'msg' => '收益日志失败'];
        }
    }

}