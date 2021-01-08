<?php

namespace app\admin\repository;

use app\admin\model\Goods;

class GoodsRepository
{
    public function get($id)
    {
        return Goods::get($id);
    }

    public function find($where)
    {
        return Goods::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return Goods::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $goods = new Goods($data);
        $res = $goods->allowField($field)->save();
        if($res){
            return $goods->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $goods = new Goods();
        return $goods->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Goods::destroy($where);
    }

    public function forceDelete($where)
    {
        return Goods::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $goods = new Goods();
        return $goods->restore($where);
    }

    public function goodsDetail($id)
    {
        return Goods::alias('g')
            ->join('yw_category c', 'c.id=g.category_id', 'left')
            ->join('yw_material m', 'm.id=g.material_id', 'left')
            ->join('yw_forum f', 'f.id=g.forum_id', 'left')
            ->field('g.*,c.name category_name,m.name material_name,f.name forum_name')
            ->get($id);
    }

    public function goodsList($param)
    {
        if($param['keyword']) {
            $where[] = ['g.title', 'like', '%'. $param['keyword'] .'%'];
        }

        return Goods::alias('g')
            ->join('yw_category c', 'c.id=g.category_id', 'left')
            ->join('yw_material m', 'm.id=g.material_id', 'left')
            ->join('yw_forum f', 'f.id=g.forum_id', 'left')
            ->where($where)
            ->field('g.id,g.state,g.title,g.pics,g.price,g.original_price,g.sales,g.hits,g.region,g.stock,g.is_separate_buy,g.create_time,c.name category_name,m.name material_name,f.name forum_name')
            ->order('id', 'desc')
            ->paginate($param['page_size'])
            ->toArray();
    }

}
