<?php

namespace app\api\service;

use think\Db;

class GoodsService extends BaseService
{
    public function indexService($param)
    {
        $list = $this->GoodsRepository->goodsList($param);
        $data = array_map(function($value) {
            $pics = explode('|', $value['pics']);
            $item = [
                'id' => $value['id'],
                'title' => $value['title'],
                'pics' => common_func_pic_domain($pics[0]),
                'price' => $value['price'],
                'original_price' => $value['original_price'],
                'sales' => $value['sales'],
                'hits' => $value['hits'],
                'original_price' => $value['original_price'],
                'create_time' => $value['create_time'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function readService($id)
    {
        $goods = $this->GoodsRepository->find([['id', '=', $id], ['state', '=', 1]]);
        if($goods) {
            $attrs = $this->AttrsRepository->select([['goods_id', '=', $id]], 'id,akey,avalue');
            $attrs_keys = [];
            foreach ($attrs as $key => $value) {
                $attrs_keys[$value['akey']][] = $value;
            }
            $goods_attrs = [];
            foreach ($attrs_keys as $key => $value) {
                $goods_attrs[] = ['spec_kind'=> $key, 'spec'=> $value];
            }
            $goods['attr'] = $goods_attrs;
            $goods['attr_price'] = $this->AttrsPriceRepository->select([['goods_id', '=', $id]], 'id,aids,price,stock');
            $goods['pics'] = common_func_pics_domain($goods['pics']);
            $goods['favorite'] = 0;

            $this->GoodsRepository->hitsInc($id);
            
            return ['status' => 1, 'msg'=> '成功', 'data'=> $goods];
        }else{
            return ['status' => 0, 'msg'=> '商品不存在或已下架'];
        }
    }
}