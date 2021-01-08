<?php

namespace app\api\service;

use think\Db;

class ShopcartService extends BaseService
{
    public function indexService($param)
    {
        $list = $this->ShopcartRepository->shopcartList($param);
        $data = array_map(function($value) {
            //$attrs = $this->AttrsRepository->getAvalueByAids($value['aids']);
            $item = [
                'id' => $value['id'],
                'pid' => $value['pid'],
                'num' => $value['num'],
                'stock' => $value['stock'],
                'price' => $value['price'],
                'title' => $value['title'],
                'pics' => common_func_pics_domain($value['pics']),
                'attrs' => $value['sku'],
            ];
            return $item;
        }, $list);
        return $data;
    }

    public function saveService($param)
    {
        $attrs_price = $this->AttrsPriceRepository->get($param['pid']);
        if($attrs_price == null) {
            return ['status'=> 0, 'msg'=> '该规格商品不存在'];
        }
        if($attrs_price['stock'] < $param['num']) {
            return ['status'=> 0, 'msg'=> '库存不足'];
        }
        $shopcart = $this->ShopcartRepository->find([['user_id', '=', $param['user_id']], ['pid', '=', $param['pid']]]);
        if($shopcart) {
            $res = $this->ShopcartRepository->numInc($shopcart['id'], $param['num']);
            if($res) {
                return ['status'=> 1, 'msg'=> '添加成功'];
            }else{
                return ['status'=> 0, 'msg'=> '添加失败'];
            }
        }else {
            $shopcart_data = [
                'user_id' => $param['user_id'],
                'pid' => $param['pid'],
                'num' => $param['num'],
            ];
            $shopcart_res = $this->ShopcartRepository->save($shopcart_data);
            if($shopcart_res) {
                return ['status'=> 1, 'msg'=> '添加成功'];
            }else{
                return ['status'=> 0, 'msg'=> '添加失败'];
            }
        }
    }

    public function updateService($param, $id)
    {
        $attrs_price = $this->AttrsPriceRepository->get($param['pid']);
        if($attrs_price == null) {
            return ['status'=> 0, 'msg'=> '该规格商品不存在'];
        }
        if($attrs_price['stock'] < $param['num']) {
            return ['status'=> 0, 'msg'=> '库存不足'];
        }
        $shopcart = $this->ShopcartRepository->find([['id', '=', $id], ['user_id', '=', $param['user_id']]]);
        if($shopcart) {
            $res = $this->ShopcartRepository->update(['num'=> $param['num']], ['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '修改成功'];
            }else{
                return ['status'=> 0, 'msg'=> '修改失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '购物商品不存在'];
        }
    }

    public function deleteService($id)
    {
        $shopcart = $this->ShopcartRepository->get($id);
        if($shopcart) {
            $res = $this->ShopcartRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '购物商品不存在'];
        }
    }

    public function batchDeleteService($param)
    {
        $idsarr = explode(',', $param['ids']);
        foreach($idsarr as $k => $v) {
            $res = $this->ShopcartRepository->softDelete(['id'=> $v, 'user_id'=> $param['user_id']]);
        }
        return ['status'=> 1, 'msg'=> '删除成功'];
    }

}