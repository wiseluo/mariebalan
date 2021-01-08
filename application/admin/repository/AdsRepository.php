<?php

namespace app\admin\repository;

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

    public function save($data, $field = true) {
        $ads = new Ads($data);
        $res = $ads->allowField($field)->save();
        if($res){
            return $ads->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $ads = new Ads();
        return $ads->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Ads::destroy($where);
    }

    public function forceDelete($where)
    {
        return Ads::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $ads = new Ads();
        return $ads->restore($where);
    }

    //幻灯片列表
    public function adsSlideList($param)
    {
        $where[] = ['type', '=', 1];
        if($param['keyword']) {
            $where[] = ['title', 'like', '%'. $param['keyword'] .'%'];
        }

        return Ads::where($where)
            ->field('id,state,title,url,pic,sort')
            ->order('sort', 'asc')
            ->paginate($param['page_size'])
            ->toArray();

    }
}
