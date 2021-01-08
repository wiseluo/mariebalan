<?php

namespace app\admin\service;

class CategoryService extends BaseService
{
    public function indexService($param)
    {
        return $this->CategoryRepository->categoryList($param);
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
        $category = $this->CategoryRepository->get($id);
        if($category){
            return ['status' => 1, 'msg'=> '成功', 'data'=> $category];
        }else{
            return ['status' => 0, 'msg'=> '品类不存在'];
        }
    }

    public function saveService($param)
    {
        $category_data = [
            'state' => $param['state'],
            'name' => $param['name'],
            'pic' => $param['pic'],
            'sort' => $param['sort'],
        ];
        $category_id = $this->CategoryRepository->save($category_data);
        if($category_id) {
            return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $category_id];
        }else{
            return ['status' => 0, 'msg' => '添加失败'];
        }
    }

    public function updateService($param, $id)
    {
        $category_data = [
            'state' => $param['state'],
            'name' => $param['name'],
            'pic' => $param['pic'],
            'sort' => $param['sort'],
        ];
        $category_res = $this->CategoryRepository->update($category_data, ['id'=> $id]);
        if($category_res) {
            return ['status'=> 1, 'msg'=> '修改成功', 'data'=> $category_res];
        }else{
            return ['status' => 0, 'msg' => '修改失败'];
        }
    }

    public function deleteService($id)
    {
        $category = $this->CategoryRepository->get($id);
        if($category) {
            $res = $this->CategoryRepository->softDelete(['id'=> $id]);
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