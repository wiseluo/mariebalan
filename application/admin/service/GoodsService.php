<?php

namespace app\admin\service;

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
                'state' => $value['state'] == 0 ? '下架' : '上架',
                'title' => $value['title'],
                'pics' => $pics[0],
                'price' => $value['price'],
                'original_price' => $value['original_price'],
                'sales' => $value['sales'],
                'hits' => $value['hits'],
                'region' => $value['region'],
                'is_separate_buy' => $value['is_separate_buy'],
                'category_name' => $value['category_name'],
                'material_name' => $value['material_name'],
                'forum_name' => $value['forum_name'],
                'create_time' => $value['create_time'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function readService($id)
    {
        $goods = $this->GoodsRepository->goodsDetail($id);
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
            $goods['attrs'] = $goods_attrs;
            $goods['attrs_price'] = $this->AttrsPriceRepository->select([['goods_id', '=', $id]], 'id,aids,sku,price,stock');

            return ['status' => 1, 'msg'=> '成功', 'data'=> $goods];
        }else{
            return ['status' => 0, 'msg'=> '商品不存在'];
        }
    }

    public function saveService($param)
    {
        Db::startTrans();
        $goods_data = [
            'category_id' => $param['category_id'],
            'material_id' => $param['material_id'],
            'forum_id' => $param['forum_id'],
            'region' => $param['region'],
            'is_separate_buy' => $param['is_separate_buy'],
            'title' => $param['title'],
            'pics' => $param['pics'],
            'price' => $param['price'],
            'original_price' => $param['original_price'],
            'content' => $param['content'],
        ];
        $goods_id = $this->GoodsRepository->save($goods_data);
        if($goods_id) {
            $attrs_res = $this->saveAttrsService($param['attrs'], $goods_id);
            if($attrs_res['status'] == 0) {
                Db::rollback();
                return ['status' => 0, 'msg' => $attrs_res['msg']];
            }
            $attrs_price_res = $this->saveAttrsPriceService($param['attrs_price'], $goods_id);
            if($attrs_price_res['status'] == 0) {
                Db::rollback();
                return ['status' => 0, 'msg' => $attrs_price_res['msg']];
            }
            Db::commit();
            return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $goods_id];
        }else{
            Db::rollback();
            return ['status' => 0, 'msg' => '商品添加失败'];
        }
    }

    public function updateService($param, $id)
    {
        Db::startTrans();
        $goods_data = [
            'category_id' => $param['category_id'],
            'material_id' => $param['material_id'],
            'forum_id' => $param['forum_id'],
            'region' => $param['region'],
            'is_separate_buy' => $param['is_separate_buy'],
            'title' => $param['title'],
            'pics' => $param['pics'],
            'price' => $param['price'],
            'original_price' => $param['original_price'],
            'content' => $param['content'],
        ];
        $goods_res = $this->GoodsRepository->update($goods_data, ['id'=> $id]);
        if($goods_res) {
            $this->AttrsRepository->softDelete(['goods_id'=> $id]);
            $this->AttrsPriceRepository->softDelete(['goods_id'=> $id]);
            $attrs_res = $this->saveAttrsService($param['attrs'], $id);
            if($attrs_res['status'] == 0) {
                Db::rollback();
                return ['status'=> 0, 'msg' => $attrs_res['msg']];
            }
            $attrs_price_res = $this->saveAttrsPriceService($param['attrs_price'], $id);
            if($attrs_price_res['status'] == 0) {
                Db::rollback();
                return ['status'=> 0, 'msg' => $attrs_price_res['msg']];
            }
            Db::commit();
            return ['status'=> 1, 'msg'=> '修改成功'];
        }else{
            return ['status'=> 0, 'msg' => '修改失败'];
        }
    }

    public function saveAttrsService($attrs_json, $goods_id)
    {
        $attrs = json_decode($attrs_json, true);
        foreach($attrs as $k => $v) {
            $attrs_data = [
                'goods_id' => $goods_id,
                'akey' => $v['akey'],
                'avalue' => $v['avalue'],
            ];
            $attrs_res = $this->AttrsRepository->save($attrs_data);
            if(!$attrs_res) {
                return ['status'=> 0, 'msg' => '规格添加失败'];
            }
        }
        return ['status'=> 1, 'msg' => '规格添加成功'];
    }

    public function saveAttrsPriceService($attrs_price_json, $goods_id)
    {
        $attrs_price = json_decode($attrs_price_json, true);
        $attrs = $this->AttrsRepository->getAvalueId($goods_id);
        foreach($attrs_price as $k => $v) {
            $sku = explode(',', $v['sku']);
            $aids = [];
            foreach ($sku as $i => $avalue) {
                $aids[] = $attrs[$avalue];
            }
            $aids_str = implode(',', $aids);
            $attrs_price_data = [
                'goods_id' => $goods_id,
                'aids' => $aids_str,
                'sku' => $v['sku'],
                'price' => $v['price'],
                'stock' => $v['stock'],
            ];
            $attrs_price_res = $this->AttrsPriceRepository->save($attrs_price_data);
            if(!$attrs_price_res) {
                return ['status'=> 0, 'msg' => '规格价格添加失败'];
            }
        }
        return ['status'=> 1, 'msg' => '规格价格添加成功'];
    }

    public function deleteService($id)
    {
        $goods = $this->GoodsRepository->get($id);
        if($goods) {
            $res = $this->GoodsRepository->softDelete(['id'=> $id]);
            $this->AttrsRepository->softDelete(['goods_id'=> $id]);
            $this->AttrsPriceRepository->softDelete(['goods_id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '商品不存在'];
        }
    }

    public function shelfService($param, $id)
    {
        $goods_res = $this->GoodsRepository->update(['state'=> $param['state']], ['id'=> $id]);
        if($goods_res) {
            return ['status'=> 1, 'msg'=> '上下架成功'];
        }else{
            return ['status'=> 0, 'msg' => '上下架失败'];
        }
    }
}