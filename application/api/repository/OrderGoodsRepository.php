<?php

namespace app\api\repository;

use app\api\model\OrderGoods;

class OrderGoodsRepository
{
    public function get($id)
    {
        return OrderGoods::get($id);
    }

    public function find($where)
    {
        return OrderGoods::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return OrderGoods::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $order_goods = new OrderGoods($data);
        $res = $order_goods->allowField($field)->save();
        if($res){
            return $order_goods->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $order_goods = new OrderGoods();
        return $order_goods->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return OrderGoods::destroy($where);
    }

    public function forceDelete($where)
    {
        return OrderGoods::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $order_goods = new OrderGoods();
        return $order_goods->restore($where);
    }

    public function getOrderGoodsNums($where)
    {
        return OrderGoods::where($where)->count('id');
    }

    public function uncommentedList($param)
    {
        $where[] = ['user_id', '=', $param['user_id']];
        $where[] = ['comment_state', '=', 1];
        return OrderGoods::field('id,title,pic,attrs')
            ->where($where)
            ->order('id desc')
            ->paginate($param['page_size'])
            ->toArray();
    }
}
