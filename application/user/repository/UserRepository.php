<?php

namespace app\user\repository;

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

    public function findByLock($where)
    {
        return User::where($where)->lock(true)->find();
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

    public function moneyInc($id, $money)
    {
        return User::where('id', $id)->setInc('money', $money);
    }

    public function moneyDec($id, $money)
    {
        return User::where('id', $id)->setDec('money', $money);
    }

    public function incomeInc($id, $income)
    {
        return User::where('id', $id)->setInc('income', $income);
    }

    public function incomeDec($id, $income)
    {
        return User::where('id', $id)->setDec('income', $income);
    }

    public function spendMoneyInc($id, $spend_money)
    {
        return User::where('id', $id)->setInc('spend_money', $spend_money);
    }

    public function userinfo($id)
    {
        return User::field('id,vip_id,phone,nickname,sex,headimgurl,money,income,referee_id')->get($id);
    }

}
