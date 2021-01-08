<?php

namespace app\user\repository;

use app\user\model\Order;

class OrderRepository
{
    public function get($id)
    {
        return Order::get($id);
    }

    public function find($where)
    {
        return Order::where($where)->find();
    }

    public function save($data, $field = true) {
        $order = new Order($data);
        $res = $order->allowField($field)->save();
        if($res){
            return $order->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $order = new Order();
        return $order->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Order::destroy($where);
    }

    public function forceDelete($where)
    {
        return Order::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $order = new Order();
        return $order->restore($where);
    }

}
