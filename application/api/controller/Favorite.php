<?php

namespace app\api\controller;

use think\Db;
use think\Controller;
use app\api\controller\Token;
use app\api\model\Func;

class Favorite extends Token
{
    public function index()
    {
        $pageIndex = request()->param('pageIndex',1);
        $pageSize = request()->param('pageSize',10);
        $favorite = Db::table('yw_favorite a')
        ->join('yw_goods b','b.id = a.gid')
        ->field('b.id,b.title,b.pics,b.price,b.stock,b.sells,b.state,b.hits,a.addtime')
        ->where('a.uid',$this->user['id'])
        ->page($pageIndex,$pageSize)
        ->select()
        ->toArray();
        return json(array('code' => 200, 'data' => Func::goods_info($favorite,true) , 'msg'=>''));
    }

    public function create()
    {
        
    }

    public function save()
    {
        $id = request()->param('id');
        $favorite = Db::table('yw_favorite')->where('uid',$this->user['id'])->whereIn('gid',$id)->find();
        if($favorite || strpos($id,',') !== false){
            $favorite = Db::table('yw_favorite')->where('uid',$this->user['id'])->whereIn('gid',$id)->delete();
                if($favorite){
                    return json(array('code' => 200, 'data' => [] , 'msg'=>'取消成功'));
                }else{
                    return json(array('code' => 400, 'data' => '' , 'msg'=>'取消失败!'));
                }
        }else{
            $goods = Db::table('yw_goods')->where('id',$id)->find();
            if($goods){
                $favorite = Db::table('yw_favorite')->data(['uid'=>$this->user['id'],'gid'=>$id,'addtime'=>time()])->insert();
                if($favorite){
                    return json(array('code' => 200, 'data' => [] , 'msg'=>'收藏成功'));
                }else{
                    return json(array('code' => 400, 'data' => '' , 'msg'=>'收藏失败!'));
                }
            }else{
                return json(array('code' => 401, 'data' => '' , 'msg'=>'商品不存在!'));
            }
        }

        // echo json_encode(array('code' => 200, 'data' => $favorite , 'msg'=>'登录失效,请重新登录！'));
        // exit;
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
        $return = Db::table('yw_favorite')->where(['uid'=>$this->user['id']])->delete();
        return json(array('code' => 200, 'data' => $return , 'msg'=>''));
    }
}
