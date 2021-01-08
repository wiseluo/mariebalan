<?php

namespace app\api\repository;

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

    public function materialList()
    {
        return Material::field('id,name,pic')->where([['state', '=', 1]])->order('sort', 'asc')->select()->toArray();
    }
    
}
