<?php

namespace app\api\repository;

use app\api\model\Order;

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

    public function select($where=[])
    {
        return Order::where($where)->select()->toArray();
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

    public function getOrderNums($where)
    {
        return Order::where($where)->count('id');
    }

    public function orderList($param)
    {
        $where[] = ['user_id', '=', $param['user_id']];
        if($param['state'] > 0) {
            $where[] = ['state', '=', $param['state']];
        }
        if($param['refund'] > 0) {
            $where[] = ['refund', '=', $param['refund']];
        }

        return Order::with('orderGoods')
            ->where($where)
            ->order('id desc')
            ->paginate($param['page_size'])
            ->toArray();
    }

    public function nextOrderNo()
    {
        return date('YmdHis').str_pad(explode('.',microtime(true))[1],4,'0');
    }

    public function orderWithGoodsDetail($id)
    {
        return Order::with('orderGoods')->get($id);
    }
}
