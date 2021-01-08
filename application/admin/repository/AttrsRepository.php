<?php

namespace app\admin\repository;

use app\admin\model\Attrs;

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

    public function select($where = [], $field = true)
    {
        return Attrs::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $attrs = new Attrs($data);
        $res = $attrs->allowField($field)->save();
        if($res){
            return $attrs->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $attrs = new Attrs();
        return $attrs->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Attrs::destroy($where);
    }

    public function forceDelete($where)
    {
        return Attrs::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $attrs = new Attrs();
        return $attrs->restore($where);
    }

    public function getAvalueId($goods_id)
    {
        return Attrs::where([['goods_id', '=', $goods_id]])->column('id','avalue');
    }
}
