<?php

namespace app\admin\repository;

use app\admin\model\Category;

class CategoryRepository
{
    public function get($id)
    {
        return Category::get($id);
    }

    public function find($where)
    {
        return Category::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return Category::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $category = new Category($data);
        $res = $category->allowField($field)->save();
        if($res){
            return $category->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $category = new Category();
        return $category->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Category::destroy($where);
    }

    public function forceDelete($where)
    {
        return Category::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $category = new Category();
        return $category->restore($where);
    }

    public function categoryList($param)
    {
        if($param['keyword']) {
            $where[] = ['name', 'like', '%'. $param['keyword'] .'%'];
        }

        return Category::where($where)
            ->field('id,state,name,pic,sort')
            ->order('sort', 'asc')
            ->paginate($param['page_size'])
            ->toArray();
    }
}
