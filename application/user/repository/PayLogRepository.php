<?php

namespace app\user\repository;

use app\user\model\PayLog;

class PayLogRepository
{
    public function get($id)
    {
        return PayLog::get($id);
    }

    public function find($where)
    {
        return PayLog::where($where)->find();
    }

    public function save($data, $field = true) {
        $pay_log = new PayLog($data);
        $res = $pay_log->allowField($field)->save();
        if($res){
            return $pay_log->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $pay_log = new PayLog();
        return $pay_log->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return PayLog::destroy($where);
    }

    public function forceDelete($where)
    {
        return PayLog::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $pay_log = new PayLog();
        return $pay_log->restore($where);
    }

}
