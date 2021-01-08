<?php

namespace app\api\repository;

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

    public function forumList()
    {
        return Forum::field('id,name,pic')->where([['state', '=', 1]])->order('sort', 'asc')->select()->toArray();
    }
    
}
