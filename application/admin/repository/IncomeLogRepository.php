<?php

namespace app\admin\repository;

use app\user\model\IncomeLog;

class IncomeLogRepository
{
    public function get($id)
    {
        return IncomeLog::get($id);
    }

    public function find($where)
    {
        return IncomeLog::where($where)->find();
    }

    public function incomeList($param)
    {
        $where = [];
        if($param['keyword']) {
            $where[] = ['User.phone|User.nickname', 'like', '%'. $param['keyword'] .'%'];
        }
        if($param['style'] > 0) {
            $where[] = ['IncomeLog.style', '=', $param['style']];
        }
        if($param['sdate'] != '' && $param['edate'] != '') {
            $where[] = ['IncomeLog.create_time', 'between time', [$param['sdate'] .' 00:00:00', $param['edate'] .' 23:59:59']];
        }

        return IncomeLog::alias('IncomeLog')
            ->where($where)
            ->join('yw_user User', 'User.id=IncomeLog.user_id', 'left')
            ->field('IncomeLog.id,IncomeLog.style,IncomeLog.user_id,IncomeLog.money,IncomeLog.title,IncomeLog.create_time,User.nickname')
            ->order('IncomeLog.id desc')
            ->paginate($param['page_size'])
            ->toArray();
    }
}
