<?php

namespace app\api\repository;

use app\api\model\Address;

class AddressRepository
{
    public function get($id)
    {
        return Address::get($id);
    }

    public function find($where)
    {
        return Address::where($where)->find();
    }

    public function select($where=[])
    {
        return Address::where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $address = new Address($data);
        $res = $address->allowField($field)->save();
        if($res){
            return $address->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $address = new Address();
        return $address->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Address::destroy($where);
    }

    public function forceDelete($where)
    {
        return Address::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $address = new Address();
        return $address->restore($where);
    }

    public function addressList($param)
    {
        $where[] = ['user_id', '=', $param['user_id']];
        return Address::where($where)->select()->toArray();
    }
}
