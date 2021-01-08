<?php

namespace app\api\service;

use think\Db;

class OrderAddressService extends BaseService
{
    public function saveService($order_id, $address_id)
    {
        $address = $this->AddressRepository->get($address_id);
        $data = [
            'order_id' => $order_id,
            'user_id' => $address['user_id'],
            'consignee' => $address['consignee'],
            'province' => $address['province'],
            'city' => $address['city'],
            'town' => $address['town'],
            'address' => $address['address'],
            'zipcode' => $address['zipcode'],
            'phone' => $address['phone'],
        ];

        $address_res = $this->OrderAddressRepository->save($data);
        if($address_res) {
            return ['status' => 1, 'msg' => '添加订单地址成功'];
        }else{
            return ['status' => 0, 'msg' => '添加订单地址失败'];
        }
    }

}