<?php

namespace app\admin\repository;

use app\admin\model\Forum;

class ForumRepository
{
    public function get($id)
    {
        return Forum::get($id);
    }

    public function find($where)
    {
        return Forum::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return Forum::field($field)->where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $forum = new Forum($data);
        $res = $forum->allowField($field)->save();
        if($res){
            return $forum->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $forum = new Forum();
        return $forum->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Forum::destroy($where);
    }

    public function forceDelete($where)
    {
        return Forum::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $forum = new Forum();
        return $forum->restore($where);
    }

    public function forumList($param)
    {
        if($param['keyword']) {
            $where[] = ['name', 'like', '%'. $param['keyword'] .'%'];
        }

        return Forum::where($where)
            ->field('id,state,name,pic,sort')
            ->order('sort', 'asc')
            ->paginate($param['page_size'])
            ->toArray();
    }
}
