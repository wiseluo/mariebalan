<?php

namespace app\admin\service;

class UserService extends BaseService
{
    public function indexService($param)
    {
        $list = $this->UserRepository->userList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'vip_name' => $value['vip_name'],
                'nickname' => $value['nickname'],
                'phone' => $value['phone'],
                'sex' => $value['sex'] == 1 ? '男' : '女',
                'money' => $value['money'],
                'income' => $value['income'],
                'create_time' => $value['create_time'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function readService($id)
    {
        $ads = $this->UserRepository->get($id);
        if($ads){
            return ['status' => 1, 'msg'=> '成功', 'data'=> $ads];
        }else{
            return ['status' => 0, 'msg'=> '品类不存在'];
        }
    }

    // public function saveService($param)
    // {
    //     $ids_data = [
    //         'state' => $param['state'],
    //         'name' => $param['name'],
    //         'pic' => $param['pic'],
    //         'sort' => $param['sort'],
    //     ];
    //     $ids_id = $this->UserRepository->save($ids_data);
    //     if($ids_id) {
    //         return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $ids_id];
    //     }else{
    //         return ['status' => 0, 'msg' => '添加失败'];
    //     }
    // }

    // public function updateService($param, $id)
    // {
    //     $ids_data = [
    //         'state' => $param['state'],
    //         'name' => $param['name'],
    //         'pic' => $param['pic'],
    //         'sort' => $param['sort'],
    //     ];
    //     $ids_res = $this->UserRepository->update($ids_data, ['id'=> $id]);
    //     if($ids_res) {
    //         return ['status'=> 1, 'msg'=> '修改成功', 'data'=> $ids_res];
    //     }else{
    //         return ['status' => 0, 'msg' => '修改失败'];
    //     }
    // }

    public function deleteService($id)
    {
        $ids = $this->UserRepository->get($id);
        if($ids) {
            $res = $this->UserRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '用户不存在'];
        }
    }

}