<?php

namespace app\admin\service;

class ExpressService extends BaseService
{
    public function indexService($param)
    {
        return $this->ExpressRepository->expressList($param);
    }

    public function readService($id)
    {
        $express = $this->ExpressRepository->get($id);
        if($express) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $express];
        }else{
            return ['status' => 0, 'msg'=> '快递公司不存在'];
        }
    }

    public function saveService($param)
    {
        $express_data = [
            'name' => $param['name'],
        ];
        $express_id = $this->ExpressRepository->save($express_data);
        if($express_id) {
            return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $express_id];
        }else{
            return ['status' => 0, 'msg' => '添加失败'];
        }
    }

    public function updateService($param, $id)
    {
        $express_data = [
            'name' => $param['name'],
        ];
        $express_res = $this->ExpressRepository->update($express_data, ['id'=> $id]);
        if($express_res) {
            return ['status'=> 1, 'msg'=> '修改成功', 'data'=> $express_res];
        }else{
            return ['status' => 0, 'msg' => '修改失败'];
        }
    }

    public function deleteService($id)
    {
        $express = $this->ExpressRepository->get($id);
        if($express) {
            $res = $this->ExpressRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '快递公司不存在'];
        }
    }

}