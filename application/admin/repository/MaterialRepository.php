<?php

namespace app\admin\repository;

use app\admin\model\Material;

class MaterialRepository
{
    public function get($id)
    {
        return Material::get($id);
    }

    public function find($where)
    {
        return Material::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return Material::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $material = new Material($data);
        $res = $material->allowField($field)->save();
        if($res){
            return $material->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $material = new Material();
        return $material->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Material::destroy($where);
    }

    public function forceDelete($where)
    {
        return Material::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $material = new Material();
        return $material->restore($where);
    }

    public function materialList($param)
    {
        if($param['keyword']) {
            $where[] = ['name', 'like', '%'. $param['keyword'] .'%'];
        }

        return Material::where($where)
            ->field('id,state,name,pic,sort')
            ->order('sort', 'asc')
            ->paginate($param['page_size'])
            ->toArray();
    }
}
