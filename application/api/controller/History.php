<?php

namespace app\api\controller;

use think\Db;
use think\Controller;
use app\api\controller\Token;
use app\api\model\Func;

class History extends Token
{
    public function index()
    {
        $pageIndex = request()->param('pageIndex',1);
        $pageSize = request()->param('pageSize',10);
        $historys = Db::table('yw_historys a')
        ->join('yw_goods b','b.id = a.gid')
        ->field('b.id,b.title,b.pics,b.price,b.sells,b.state,b.hits,a.addtime')
        ->where('a.uid',$this->user['id'])
        ->order('addtime','desc')
        ->page($pageIndex,$pageSize)
        ->select();
        return json(array('code' => 200, 'data' => Func::goods_info($historys,true) , 'msg'=>''));
    }

    public function create()
    {
        
    }

    public function save()
    {

    }

    public function read($id)
    {
        
    }

    public function edit($id)
    {
        
    }

    public function update($id)
    {
        
    }

    public function delete($id)
    {
        $ids = request()->param('ids');
        $return = Db::table('yw_historys')->where('uid',$this->user['id'])->where(
            function ($query) use($ids) {
                if($ids){
                    $query->whereIn('gid',$ids);
                }
            }
        )->delete();
        return json(array('code' => 200, 'data' => $return , 'msg'=>''));
    }
}
