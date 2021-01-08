<?php

namespace app\api\repository;

use app\api\model\OrderAddress;

class OrderAddressRepository
{
    public function get($id)
    {
        return OrderAddress::get($id);
    }

    public function find($where)
    {
        return OrderAddress::where($where)->find();
    }

    public function select($where=[])
    {
        return OrderAddress::where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $order_address = new OrderAddress($data);
        $res = $order_address->allowField($field)->save();
        if($res){
            return $order_address->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $order_address = new OrderAddress();
        return $order_address->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return OrderAddress::destroy($where);
    }

    public function forceDelete($where)
    {
        return OrderAddress::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $order_address = new OrderAddress();
        return $order_address->restore($where);
    }

}
