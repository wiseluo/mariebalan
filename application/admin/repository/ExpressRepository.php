<?php

namespace app\admin\repository;

use app\admin\model\Express;

class ExpressRepository
{
    public function get($id)
    {
        return Express::get($id);
    }

    public function find($where)
    {
        return Express::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return Express::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $express = new Express($data);
        $res = $express->allowField($field)->save();
        if($res){
            return $express->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $express = new Express();
        return $express->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Express::destroy($where);
    }

    public function forceDelete($where)
    {
        return Express::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $express = new Express();
        return $express->restore($where);
    }

    public function expressList($param)
    {
        if($param['keyword']) {
            $where[] = ['name', 'like', '%'. $param['keyword'] .'%'];
        }

        return Express::where($where)
            ->field('id,name')
            ->select()
            ->toArray();
    }
}
