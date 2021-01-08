<?php

namespace app\api\repository;

use app\api\model\Goods;

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

    public function select($where=[])
    {
        return Goods::where($where)->select()->toArray();
    }

    public function hitsInc($id)
    {
        return Goods::where('id', $id)->setInc('hits', 1);
    }

    public function salesInc($id, $num)
    {
        return Goods::where('id', $id)->setInc('sales', $num);
    }

    public function goodsList($param)
    {
        $where[] = ['state', '=', 1];
        if($param['region']) {
            $where[] = ['region', '=', $param['region']];
        }
        if($param['category_id']) {
            $where[] = ['category_id', '=', $param['category_id']];
        }
        if($param['material_id']) {
            $where[] = ['material_id', '=', $param['material_id']];
        }
        if($param['forum_id']) {
            $where[] = ['forum_id', '=', $param['forum_id']];
        }
        if($param['keyword']) {
            $where[] = ['title', 'LIKE', '%'. $param['keyword'] .'%'];
        }

        return Goods::where($where)
            ->order($param['orderby'], $param['sort'])
            ->paginate($param['page_size'])
            ->toArray();
    }


}
