<?php

namespace app\api\repository;

use app\admin\model\Ads;

class AdsRepository
{
    public function get($id)
    {
        return Ads::get($id);
    }

    public function find($where)
    {
        return Ads::where($where)->find();
    }

    public function select($where = [], $field = true)
    {
        return Ads::field($field)->where($where)->select()->toArray();
    }

    public function adsSlideList()
    {
        return Ads::field('id,title,pic')->where([['type', '=', 1], ['state', '=', 1]])->order('sort', 'asc')->select()->toArray();
    }
    
}
