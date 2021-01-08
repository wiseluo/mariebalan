<?php

namespace app\user\repository;

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

    public function save($data, $field = true) {
        $income_log = new IncomeLog($data);
        $res = $income_log->allowField($field)->save();
        if($res){
            return $income_log->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $income_log = new IncomeLog();
        return $income_log->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return IncomeLog::destroy($where);
    }

    public function forceDelete($where)
    {
        return IncomeLog::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $income_log = new IncomeLog();
        return $income_log->restore($where);
    }

    public function logList($param)
    {
        $where[] = ['user_id', '=', $param['user_id']];
        if($param['style'] > 0) {
            $where[] = ['style', '=', $param['style']];
        }
        if($param['sdate'] != '' && $param['edate'] != '') {
            $where[] = ['create_time', 'between time', [$param['sdate'] .' 00:00:00', $param['edate'] .' 23:59:59']];
        }

        return IncomeLog::where($where)
            ->order('id desc')
            ->paginate($param['page_size'])
            ->toArray();
    }
}
