<?php

namespace app\admin\service;

class MaterialService extends BaseService
{
    public function indexService($param)
    {
        return $this->MaterialRepository->materialList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'state' => $value['state'],
                'name' => $value['name'],
                'pic' => $value['pic'],
                'url' => $value['url'],
                'sort' => $value['sort'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function readService($id)
    {
        $material = $this->MaterialRepository->get($id);
        if($material){
            return ['status' => 1, 'msg'=> '成功', 'data'=> $material];
        }else{
            return ['status' => 0, 'msg'=> '品类不存在'];
        }
    }

    public function saveService($param)
    {
        $material_data = [
            'state' => $param['state'],
            'name' => $param['name'],
            'pic' => $param['pic'],
            'sort' => $param['sort'],
        ];
        $material_id = $this->MaterialRepository->save($material_data);
        if($material_id) {
            return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $material_id];
        }else{
            return ['status' => 0, 'msg' => '添加失败'];
        }
    }

    public function updateService($param, $id)
    {
        $material_data = [
            'state' => $param['state'],
            'name' => $param['name'],
            'pic' => $param['pic'],
            'sort' => $param['sort'],
        ];
        $material_res = $this->MaterialRepository->update($material_data, ['id'=> $id]);
        if($material_res) {
            return ['status'=> 1, 'msg'=> '修改成功', 'data'=> $material_res];
        }else{
            return ['status' => 0, 'msg' => '修改失败'];
        }
    }

    public function deleteService($id)
    {
        $material = $this->MaterialRepository->get($id);
        if($material) {
            $res = $this->MaterialRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '品类不存在'];
        }
    }

}