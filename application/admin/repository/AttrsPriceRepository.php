<?php

namespace app\admin\repository;

use app\admin\model\AttrsPrice;

class AttrsPriceRepository
{
    public function get($id)
    {
        return AttrsPrice::get($id);
    }

    public function find($where)
    {
        return AttrsPrice::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return AttrsPrice::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $attrs_price = new AttrsPrice($data);
        $res = $attrs_price->allowField($field)->save();
        if($res){
            return $attrs_price->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $attrs_price = new AttrsPrice();
        return $attrs_price->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return AttrsPrice::destroy($where);
    }

    public function forceDelete($where)
    {
        return AttrsPrice::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $attrs_price = new AttrsPrice();
        return $attrs_price->restore($where);
    }

}
