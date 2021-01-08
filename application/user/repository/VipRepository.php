<?php

namespace app\user\repository;

use app\user\model\Vip;

class VipRepository
{
    public function get($id)
    {
        return Vip::get($id);
    }

    public function find($where)
    {
        return Vip::where($where)->find();
    }

    public function getLastVip($value)
    {
        return Vip::field('id')->where('value', '<=', $value)->order('value desc')->find();
    }

    public function save($data, $field = true) {
        $vip = new Vip($data);
        $res = $vip->allowField($field)->save();
        if($res){
            return $vip->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $vip = new Vip();
        return $vip->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Vip::destroy($where);
    }

    public function forceDelete($where)
    {
        return Vip::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $vip = new Vip();
        return $vip->restore($where);
    }

}
