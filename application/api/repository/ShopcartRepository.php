<?php

namespace app\api\repository;

use app\api\model\Shopcart;

class ShopcartRepository
{
    public function get($id)
    {
        return Shopcart::get($id);
    }

    public function find($where)
    {
        return Shopcart::where($where)->find();
    }

    public function select($where=[])
    {
        return Shopcart::where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $shopcart = new Shopcart($data);
        $res = $shopcart->allowField($field)->save();
        if($res){
            return $shopcart->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $shopcart = new Shopcart();
        return $shopcart->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Shopcart::destroy($where);
    }

    public function forceDelete($where)
    {
        return Shopcart::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $shopcart = new Shopcart();
        return $shopcart->restore($where);
    }

    public function numInc($id, $num)
    {
        return Shopcart::where('id', $id)->setInc('num', $num);
    }

    public function getShopcartNums($where)
    {
        return Shopcart::where($where)->count('id');
    }

    public function shopcartList($param)
    {
        $where[] = ['user_id', '=', $param['user_id']];

        return Shopcart::alias('Shopcart')
            ->join('yw_attrs_price AP', 'AP.id=Shopcart.pid', 'left')
            ->join('yw_goods Goods', 'AP.goods_id=Goods.id')
            ->field('Shopcart.id,Shopcart.pid,Shopcart.num,AP.aids,AP.sku,AP.stock,AP.price,Goods.title,Goods.pics')
            ->where($where)
            ->order('Shopcart.id desc')
            ->select()
            ->toArray();
    }

}
