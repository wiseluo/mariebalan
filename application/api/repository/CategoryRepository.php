<?php

namespace app\api\repository;

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

    public function categoryList()
    {
        return Category::field('id,name,pic')->where([['state', '=', 1]])->order('sort', 'asc')->select()->toArray();
    }
    
}
