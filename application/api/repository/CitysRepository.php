<?php

namespace app\api\repository;

use app\api\model\Citys;

class CitysRepository
{
    public function get($id)
    {
        return Citys::get($id);
    }

    public function find($where)
    {
        return Citys::where($where)->find();
    }

    public function select($where)
    {
        return Citys::where($where)->select()->toArray();
    }

    public function getNameByID($id)
    {
        return Citys::where('id', $id)->value('name');
    }

}
