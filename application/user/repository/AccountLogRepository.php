<?php

namespace app\user\repository;

use app\user\model\AccountLog;

class AccountLogRepository
{
    public function get($id)
    {
        return AccountLog::get($id);
    }

    public function find($where)
    {
        return AccountLog::where($where)->find();
    }

    public function save($data, $field = true) {
        $accountLog = new AccountLog($data);
        $res = $accountLog->allowField($field)->save();
        if($res){
            return $accountLog->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $accountLog = new AccountLog();
        return $accountLog->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return AccountLog::destroy($where);
    }

    public function forceDelete($where)
    {
        return AccountLog::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $accountLog = new AccountLog();
        return $accountLog->restore($where);
    }

}
