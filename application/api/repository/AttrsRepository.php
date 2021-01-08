<?php

namespace app\api\repository;

use app\api\model\Attrs;

class AttrsRepository
{
    public function get($id)
    {
        return Attrs::get($id);
    }

    public function find($where)
    {
        return Attrs::where($where)->find();
    }

    public function select($where=[], $field='*')
    {
        return Attrs::field($field)->where($where)->select()->toArray();
    }

    public function getAvalueByAids($aids)
    {
        return Attrs::whereIn('id', $aids)->column('avalue');
    }
}
