<?php

namespace app\admin\repository;

use app\admin\model\Module;

class ModuleRepository
{
    public function get($id)
    {
        return Module::get($id);
    }

    public function find($where)
    {
        return Module::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return Module::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $module = new Module($data);
        $res = $module->allowField($field)->save();
        if($res){
            return $module->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $module = new Module();
        return $module->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Module::destroy($where);
    }

    public function forceDelete($where)
    {
        return Module::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $module = new Module();
        return $module->restore($where);
    }

    public function moduleList($param)
    {
        if($param['keyword']) {
            $where[] = ['name', 'like', '%'. $param['keyword'] .'%'];
        }

        return Module::where($where)
            ->field('id,fid,state,name,mod,cssname,cssico,sort')
            ->order('sort', 'asc')
            ->paginate($param['page_size'])
            ->toArray();
    }

    public function leftModuleList()
    {
        return Module::where([['state', '=', 1]])
            ->field('id,fid,name,mod,cssname,cssico')
            ->order('sort', 'asc')
            ->select()
            ->toArray();
    }
}
