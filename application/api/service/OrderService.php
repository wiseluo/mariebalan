<?php

namespace app\api\service;

use think\Db;

class OrderService extends BaseService
{
    public function indexService($param)
    {
        $list = $this->OrderRepository->orderList($param);
        $data = array_map(function($value) {
            $order_goods = [];
            foreach($value['order_goods'] as $k => $goods) {
                $goods_item = [
                    'id' => $goods['id'],
                    'title' => $goods['title'],
                    'pic' => $goods['pic'],
                    'price' => $goods['price'],
                    'num' => $goods['num'],
                    'attrs' => $goods['attrs'],
                ];
                $order_goods[] = $goods_item;
            }
            $item = [
                'id' => $value['id'],
                'state' => $value['state'],
                'number' => $value['number'],
                'money' => $value['money'],
                'create_time' => $value['create_time'],
                'order_goods' => $order_goods,
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function readService($id)
    {
        $order = $this->OrderRepository->orderWithGoodsDetail($id);
        if($order) {
            $order['address'] = $this->OrderAddressRepository->find([['order_id', '=', $order['id']]]);
            return ['status' => 1, 'msg'=> '成功', 'data'=> $order];
        }else{
            return ['status' => 0, 'msg'=> '订单不存在'];
        }
    }

    public function orderNowPrejudgeService($param)
    {
        $attrs_price = $this->AttrsPriceRepository->getAttrsPriceWithGoods($param['pid']);
        if($attrs_price['state'] == 0) {
            return ['status' => 0, 'msg'=> '该商品已下架'];
        }else if($attrs_price['is_separate_buy'] == 2) {
            return ['status' => 0, 'msg'=> '该商品不支持单独购买'];
        }
        return ['status'=> 1, 'msg'=> '可以下单'];
    }

    public function orderNowService($param)
    {
        $goods_arr = [];
        $attrs_price = $this->AttrsPriceRepository->getAttrsPriceWithGoods($param['pid']);
        $attrs_goods = [
            'pid' => $param['pid'],
            'title' => $attrs_price['title'],
            'weight' => $attrs_price['weight'],
            'pics' => common_func_pics_domain($attrs_price['pics']),
            'num' => $param['num'],
            'price' => $attrs_price['price'],
            'stock' => $attrs_price['stock'],
            'scid' => '',
        ];
        //$attrs = $this->AttrsRepository->getAvalueByAids($attrs_price['aids']);
        $attrs_goods['attrs'] = $attrs_price['sku'];

        $goods_arr[] = $attrs_goods;
        $sum = bcmul($attrs_price['price'], $param['num'],2);
        return ['goods'=> $goods_arr, 'sum'=> $sum];
    }

    public function shopcartSettlementPrejudgeService($param)
    {
        $is_separate_buy = 2;
        $scids = explode(',', $param['scids']);
        foreach($scids as $k => $v) {
            $shopcart = $this->ShopcartRepository->find([['id', '=', $v], ['user_id', '=', $param['user_id']]]);
            if($shopcart == null) {
                continue;
            }
            $attrs_price = $this->AttrsPriceRepository->getAttrsPriceWithGoods($shopcart['pid']);
            if($attrs_price['state'] == 0) {
                return ['status' => 0, 'msg'=> $attrs_price['title'] .'-商品已下架'];
            }
            if($attrs_price['is_separate_buy'] == 1) {
                $is_separate_buy = 1;
            }
        }
        if($is_separate_buy == 2) {
            return ['status' => 0, 'msg'=> '商品不支持单独购买'];
        }
        return ['status'=> 1, 'msg'=> '可以下单'];
    }

    public function shopcartSettlementService($param)
    {
        $goods_arr = [];
        $sum = 0;
        $scids = explode(',', $param['scids']);
        foreach($scids as $k => $v) {
            $shopcart = $this->ShopcartRepository->find([['id', '=', $v], ['user_id', '=', $param['user_id']]]);
            if($shopcart == null) {
                continue;
            }
            $attrs_price = $this->AttrsPriceRepository->getAttrsPriceWithGoods($shopcart['pid']);
            $attrs_goods = [
                'pid' => $shopcart['pid'],
                'title' => $attrs_price['title'],
                'weight' => $attrs_price['weight'],
                'pics' => common_func_pics_domain($attrs_price['pics']),
                'num' => $shopcart['num'],
                'price' => $attrs_price['price'],
                'stock' => $attrs_price['stock'],
                'scid' => $v,
            ];
            //$attrs = $this->AttrsRepository->getAvalueByAids($attrs_price['aids']);
            $attrs_goods['attrs'] = $attrs_price['sku'];
            $goods_arr[] = $attrs_goods;
            $sum = bcadd($sum, bcmul($attrs_price['price'], $shopcart['num'], 2), 2);
        }
        return ['goods'=> $goods_arr, 'sum'=> $sum];
    }

    public function saveService($param)
    {
        $goods = json_decode($param['goods'], true);

        $sum = 0;
        $content = '';
        $goods_arr = [];
        foreach($goods as $k => $v) {
            $attrs_price = $this->AttrsPriceRepository->getAttrsPriceWithGoods($v['pid']);
            if(!$attrs_price || $attrs_price['state'] == 0) {
                return ['status'=> 1, 'msg'=> $attrs_price['title'] .'-已下架或删除'];
            }
            //$attrs = $this->AttrsRepository->getAvalueByAids($attrs_price['aids']);

            $pics = explode('|', $attrs_price['pics']);
            $total_price = bcmul($attrs_price['price'], $v['num'], 2);
            $item = [
                'user_id' => $param['user_id'],
                'goods_id' => $attrs_price['goods_id'],
                'pid' => $v['pid'],
                'sku' => $attrs_price['sku'],
                'title' => $attrs_price['title'],
                'num' => $v['num'],
                'price' => $attrs_price['price'],
                'total_price' => $total_price,
                'pic' => common_func_pic_domain($pics[0]),
                'attrs' => $attrs_price['sku'],
            ];
            $goods_arr[] = $item;
            $sum = bcadd($sum, $total_price, 2);
            $content .= $attrs_price['title'].' x'.$v['num'] .' ';
        }
        Db::startTrans();
        $order_data = [
            'state' => 1,
            'number' => $this->OrderRepository->nextOrderNo(),
            'user_id' => $param['user_id'],
            'nickname' => request()->user()['nickname'],
            'money' => $sum,
            'pay_money' => $sum,
            'content' => $content,
        ];
        //实付金额 减去优惠券金额
        if($param['user_coupon_id'] > 0) {
            $user_coupon = model('user/UserCouponRepository', 'repository')->get($user_coupon_id);
            $pay_money = bcsub($order_data['money'], $user_coupon['money'], 2);
            if($pay_money <= 0) {
                return ['status'=> 0, 'msg'=> '优惠金额错误'];
            }
            $order_data['pay_money'] = $pay_money;
        }
        $order_id = $this->OrderRepository->save($order_data);
        if($order_id) {
            //添加产品
            foreach($goods_arr as $k => $v) {
                $v['order_id'] = $order_id;
                $goods_res = $this->OrderGoodsRepository->save($v);
                if(!$goods_res) {
                    Db::rollback();
                    return ['status'=> 0, 'msg'=> $v['title'] .'-添加失败'];
                }
            }
            //订单地址
            $address_res = model('api/OrderAddressService', 'service')->saveService($order_id, $param['address_id']);
            if($address_res['status'] == 0) {
                Db::rollback();
                return ['status'=> 0, 'msg'=> '下单地址添加失败'];
            }
            //使用优惠券
            if($param['user_coupon_id'] > 0) {
                $coupon_res = model('api/OrderCouponService', 'service')->saveService($order_id, $param['user_coupon_id']);
                if($coupon_res['status'] == 0) {
                    Db::rollback();
                    return ['status'=> 0, 'msg'=> '优惠券添加失败'];
                }
            }
            //删除购物车
            foreach($goods_arr as $k => $v) {
                $shopcart = $this->ShopcartRepository->find([['user_id', '=', $param['user_id']], ['pid', '=', $v['pid']]]);
                if($shopcart) {
                    $this->ShopcartRepository->softDelete(['id'=> $shopcart['id']]);
                }
            }
            Db::commit();
            return ['status'=> 1, 'msg'=> '下单成功', 'data'=> $order_id];
        }else{
            Db::rollback();
            return ['status' => 0, 'msg' => '下单失败'];
        }
    }

    public function deleteService($id)
    {
        $order = $this->OrderRepository->get($id);
        if($order) {
            if($order['state'] != 4 && $order['state'] != 5) { //已完成或已关闭订单可删除
                return ['status'=> 0, 'msg'=> '订单状态错误'];
            }
            $res = $this->OrderRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '订单删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '订单删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
    }

    public function orderRemindDeliverService($id)
    {
        $order = $this->OrderRepository->get($id);
        if($order) {
            if($order['state'] != 2) { //已付款,待发货 可提醒发货
                return ['status'=> 0, 'msg'=> '订单状态错误'];
            }
            $res = $this->OrderRepository->update(['reminder'=> 2], ['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '提醒发货成功'];
            }else{
                return ['status'=> 0, 'msg'=> '提醒发货失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
    }

    public function orderConfirmReceiptService($id)
    {
        $order = $this->OrderRepository->get($id);
        if($order) {
            if($order['state'] != 3) { //已发货 可确认收货
                return ['status'=> 0, 'msg'=> '订单状态错误'];
            }
            $order_res = $this->OrderRepository->update(['state'=> 4], ['id'=> $id]);
            $goods_res = $this->OrderGoodsRepository->update(['comment_state'=> 1], ['order_id'=> $id]);
            if($order_res && $goods_res) {
                return ['status'=> 1, 'msg'=> '确认收货成功'];
            }else{
                return ['status'=> 0, 'msg'=> '确认收货失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
    }

    public function orderCancelService($id)
    {
        $order = $this->OrderRepository->get($id);
        if($order) {
            if($order['state'] != 1) { //待付款订单可取消
                return ['status'=> 0, 'msg'=> '订单状态错误'];
            }
            $res = $this->OrderRepository->update(['state'=> 5], ['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '订单取消成功'];
            }else{
                return ['status'=> 0, 'msg'=> '订单取消失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
    }

    public function orderExpressService($id)
    {
        $order = $this->OrderRepository->get($id);
        if($order) {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> ['express_name'=> $order['express_name'], 'express_no'=> $order['express_no']]];
        }else{
            return ['status'=> 0, 'msg'=> '订单不存在'];
        }
    }

    public function uncommentedListService($param)
    {
        return $this->OrderGoodsRepository->uncommentedList($param);
    }
}