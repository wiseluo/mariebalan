<?php

namespace app\admin\repository;

use app\user\model\User;

class UserRepository
{
    public function get($id)
    {
        return User::get($id);
    }

    public function find($where)
    {
        return User::where($where)->find();
    }

    public function save($data, $field = true) {
        $user = new User($data);
        $res = $user->allowField($field)->save();
        if($res){
            return $user->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $user = new User();
        return $user->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return User::destroy($where);
    }

    public function forceDelete($where)
    {
        return User::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $user = new User();
        return $user->restore($where);
    }

    public function userList($param)
    {
        if($param['keyword']) {
            $where[] = ['User.nickname', 'like', '%'. $param['keyword'] .'%'];
        }

        return User::alias('User')
            ->join('yw_vip Vip', 'Vip.id=User.vip_id', 'left')
            ->field('User.*,Vip.name vip_name')
            ->where($where)
            ->order('User.id', 'desc')
            ->paginate($param['page_size'])
            ->toArray();
    }

}
