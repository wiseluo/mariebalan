<?php

namespace app\admin\repository;

use app\admin\model\Admin;

class AdminRepository
{
    public function get($id)
    {
        return Admin::get($id);
    }

    public function find($where)
    {
        return Admin::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return Admin::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $admin = new Admin($data);
        $res = $admin->allowField($field)->save();
        if($res){
            return $admin->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $admin = new Admin();
        return $admin->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Admin::destroy($where);
    }

    public function forceDelete($where)
    {
        return Admin::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $admin = new Admin();
        return $admin->restore($where);
    }

}
