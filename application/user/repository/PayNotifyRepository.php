<?php

namespace app\user\repository;

use app\user\model\PayNotify;

class PayNotifyRepository
{
    public function get($id)
    {
        return PayNotify::get($id);
    }

    public function find($where)
    {
        return PayNotify::where($where)->find();
    }

    public function save($data, $field = true) {
        $payNotify = new PayNotify($data);
        $res = $payNotify->allowField($field)->save();
        if($res){
            return $payNotify->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $payNotify = new PayNotify();
        return $payNotify->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return PayNotify::destroy($where);
    }

    public function forceDelete($where)
    {
        return PayNotify::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $payNotify = new PayNotify();
        return $payNotify->restore($where);
    }

}
