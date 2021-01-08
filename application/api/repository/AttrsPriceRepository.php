<?php

namespace app\api\repository;

use app\api\model\AttrsPrice;

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

    public function select($where=[], $field='*')
    {
        return AttrsPrice::field($field)->where($where)->select()->toArray();
    }

    public function stockDec($id, $num)
    {
        return Goods::where('id', $id)->setDec('stock', $num);
    }

    public function getAttrsPriceWithGoods($id)
    {
        return AttrsPrice::alias('AttrsPrice')
            ->join('yw_goods Goods', 'AttrsPrice.goods_id=Goods.id', 'left')
            ->field('AttrsPrice.id,AttrsPrice.goods_id,AttrsPrice.aids,AttrsPrice.sku,AttrsPrice.price,AttrsPrice.stock,Goods.title,Goods.weight,Goods.pics,Goods.state,Goods.is_separate_buy')
            ->where('AttrsPrice.id', $id)
            ->find();
    }
}
