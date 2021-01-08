<?php

namespace app\admin\service;

class IncomeService extends BaseService
{
    public function indexService($param)
    {
        return $this->IncomeLogRepository->incomeList($param);
    }

    public function readService($id)
    {
        $income = $this->IncomeLogRepository->get($id);
        if($income){
            return ['status' => 1, 'msg'=> '成功', 'data'=> $income];
        }else{
            return ['status' => 0, 'msg'=> '收益不存在'];
        }
    }

}