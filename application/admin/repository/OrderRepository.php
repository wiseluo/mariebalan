<?php

namespace app\admin\repository;

use app\admin\model\Order;

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

    public function select($where = [], $field = true)
    {
        return Order::field($field)->where($where)->select()->toArray();
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

    public function orderList($param)
    {
        if($param['keyword']) {
            $where[] = ['nickname', 'like', '%'. $param['keyword'] .'%'];
        }
        if($param['state'] > 0) {
            $where[] = ['state', '=', $param['state']];
        }

        return Order::where($where)
            ->field('id,state,user_id,nickname,number,money,pay_money,create_time')
            ->order('id', 'desc')
            ->paginate($param['page_size'])
            ->toArray();
    }

    public function toDeliverOrderList($param)
    {
        $where[] = ['state', '=', 2];
        if($param['keyword']) {
            $where[] = ['o.nickname', 'like', '%'. $param['keyword'] .'%'];
        }

        return Order::alias('o')
            ->join('yw_order_address a', 'a.order_id=o.id', 'left')
            ->where($where)
            ->field('o.id,o.nickname,o.number,o.money,o.pay_money,o.create_time,a.consignee,a.province,a.city,a.town,a.address,a.phone,a.remark')
            ->order('o.id', 'desc')
            ->paginate($param['page_size'])
            ->toArray();
    }

    public function orderWithGoodsDetail($id)
    {
        return Order::with('orderGoods,orderAddress')->get($id);
    }
}
