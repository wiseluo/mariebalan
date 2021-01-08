<?php

namespace app\api\service;

class AddressService extends BaseService
{
    public function indexService($param)
    {
        return $this->AddressRepository->addressList($param);
    }

    public function readService($id)
    {
        $user_id = request()->user()['id'];
        $address = $this->AddressRepository->find([['id', '=', $id], ['user_id', '=', $user_id]]);
        if($address) {
            return ['status'=> 1, 'msg'=> '', 'data'=> $address];
        }else{
            return ['status'=> 0, 'msg'=> '地址不存在'];
        }
    }

    public function userDefaultAddressService()
    {
        $user_id = request()->user()['id'];
        $address = $this->AddressRepository->find([['isdefault', '=', 1], ['user_id', '=', $user_id]]);
        if($address) {
            return ['status'=> 1, 'msg'=> '', 'data'=> $address];
        }else{
            return ['status'=> 0, 'msg'=> '未设置默认地址'];
        }
    }

    public function saveService($param)
    {
        if($param['isdefault'] == 1) {
            $this->AddressRepository->update(['isdefault'=> 0], ['user_id' => $param['user_id']]);
        }
        $data = [
            'user_id' => $param['user_id'],
            'consignee' => $param['consignee'],
            'address' => $param['address'],
            'zipcode' => $param['zipcode'],
            'phone' => $param['phone'],
            'province_id' => $param['province_id'],
            'city_id' => $param['city_id'],
            'town_id' => $param['town_id'],
            'isdefault' => $param['isdefault'],
        ];
        $data['province'] = $this->CitysRepository->getNameByID($param['province_id']);
        $data['city'] = $this->CitysRepository->getNameByID($param['city_id']);
        $data['town'] = $this->CitysRepository->getNameByID($param['town_id']);

        $address_id = $this->AddressRepository->save($data);
        if($address_id) {
            return ['status' => 1, 'msg' => '添加地址成功', 'data'=> $address_id];
        }else{
            return ['status' => 0, 'msg' => '添加地址失败'];
        }
    }

    public function updateService($param, $id)
    {
        if($param['isdefault'] == 1) {
            $this->AddressRepository->update(['isdefault'=> 0], ['user_id' => $param['user_id']]);
        }
        $data = [
            'user_id' => $param['user_id'],
            'consignee' => $param['consignee'],
            'address' => $param['address'],
            'zipcode' => $param['zipcode'],
            'phone' => $param['phone'],
            'province_id' => $param['province_id'],
            'city_id' => $param['city_id'],
            'town_id' => $param['town_id'],
            'isdefault' => $param['isdefault'],
        ];
        $data['province'] = $this->CitysRepository->getNameByID($param['province_id']);
        $data['city'] = $this->CitysRepository->getNameByID($param['city_id']);
        $data['town'] = $this->CitysRepository->getNameByID($param['town_id']);

        $address_res = $this->AddressRepository->update($data, ['id'=> $id, 'user_id'=> $param['user_id']]);
        if($address_res) {
            return ['status' => 1, 'msg' => '修改地址成功', 'data'=> $id];
        }else{
            return ['status' => 0, 'msg' => '修改地址失败'];
        }
    }

    public function deleteService($id)
    {
        $user_id = request()->user()['id'];
        $address_res = $this->AddressRepository->softDelete(['id'=> $id, 'user_id'=> $user_id]);
        if($address_res) {
            return ['status' => 1, 'msg' => '删除地址成功'];
        }else{
            return ['status' => 0, 'msg' => '删除地址失败'];
        }
    }
}