<?php

namespace app\admin\service;

class ModuleService extends BaseService
{
    public function indexService($param)
    {
        return $this->ModuleRepository->moduleList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'fid' => $value['fid'],
                'state' => $value['state'],
                'name' => $value['name'],
                'mod' => $value['mod'],
                'cssname' => $value['cssname'],
                'cssico' => $value['cssico'],
                'sort' => $value['sort'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function leftModuleService()
    {
        return $this->ModuleRepository->leftModuleList();
    }

    public function readService($id)
    {
        $module = $this->ModuleRepository->get($id);
        if($module) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $module];
        }else{
            return ['status' => 0, 'msg'=> '品类不存在'];
        }
    }

    public function saveService($param)
    {
        $module_data = [
            'state' => $param['state'],
            'name' => $param['name'],
            'pic' => $param['pic'],
            'sort' => $param['sort'],
        ];
        $module_id = $this->ModuleRepository->save($module_data);
        if($module_id) {
            return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $module_id];
        }else{
            return ['status' => 0, 'msg' => '添加失败'];
        }
    }

    public function updateService($param, $id)
    {
        $module_data = [
            'state' => $param['state'],
            'name' => $param['name'],
            'pic' => $param['pic'],
            'sort' => $param['sort'],
        ];
        $module_res = $this->ModuleRepository->update($module_data, ['id'=> $id]);
        if($module_res) {
            return ['status'=> 1, 'msg'=> '修改成功', 'data'=> $module_res];
        }else{
            return ['status' => 0, 'msg' => '修改失败'];
        }
    }

    public function deleteService($id)
    {
        $module = $this->ModuleRepository->get($id);
        if($module) {
            $res = $this->ModuleRepository->softDelete(['id'=> $id]);
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