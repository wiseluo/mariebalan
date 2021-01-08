<?php

namespace app\user\repository;

use app\user\model\Sms;

class SmsRepository
{
    public function get($id)
    {
        return Sms::get($id);
    }

    public function find($where)
    {
        return Sms::where($where)->find();
    }

    public function save($data, $field = true) {
        $sms = new Sms($data);
        $res = $sms->allowField($field)->save();
        if($res){
            return $sms->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $sms = new Sms();
        return $sms->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Sms::destroy($where);
    }

    public function forceDelete($where)
    {
        return Sms::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $sms = new Sms();
        return $sms->restore($where);
    }

    public function getLastOne($where)
    {
        return Sms::where($where)->order('id', 'desc')->find();
    }
}
