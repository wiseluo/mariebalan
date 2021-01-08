<?php

namespace app\user\repository;

use app\user\model\RechargeOrder;

class RechargeOrderRepository
{
    public function get($id)
    {
        return RechargeOrder::get($id);
    }

    public function find($where)
    {
        return RechargeOrder::where($where)->find();
    }

    public function save($data, $field = true) {
        $recharge_order = new RechargeOrder($data);
        $res = $recharge_order->allowField($field)->save();
        if($res){
            return $recharge_order->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $recharge_order = new RechargeOrder();
        return $recharge_order->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return RechargeOrder::destroy($where);
    }

    public function forceDelete($where)
    {
        return RechargeOrder::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $recharge_order = new RechargeOrder();
        return $recharge_order->restore($where);
    }

    //生成充值订单号
    public function nextRechargeOrderNo()
    {
        return 'CZ' . date('YmdHis').str_pad(explode('.',microtime(true))[1],4,'0');
    }
}
