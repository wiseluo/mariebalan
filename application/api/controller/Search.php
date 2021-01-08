<?php

namespace app\api\controller;

use think\Db;
use think\Controller;
use app\api\model\Func;
//use app\api\model\Api;

class Search extends Controller
{
    public function index()
    {
        $token = request()->param('token');
        $pageIndex = request()->param('pageIndex',1);
        $pageSize = request()->param('pageSize',10);
        $sort = request()->param('sort','desc');
        $tid = request()->param('tid',0);
        $by = request()->param('by','id');
        $keyword = request()->param('keyword');

        $goods = Db::table('yw_goods')->where(
            function ($query) use($keyword,$tid) {
                if($tid > 0){
                    $tids = Db::table('yw_types')->where('fid',$tid)->column('id');
                    if($tids){
                        $query->whereIn('tid',implode(',',$tids));
                    }else{
                        $query->where('tid',$tid);
                    }
                }
                if($keyword){
                    $query->where('title','like',"%$keyword%");
                }
            }
        )->where('state',1)->order([$by=>$sort])->page($pageIndex,$pageSize)->select();
        foreach ($goods as $key => $value) {
            if(isset($value['addtime'])){
                $goods[$key]['adddate'] = date('Y-m-d',$value['addtime']);
            }
            $pics = explode('|', $value['pics']);
            $goods[$key]['pics'] = Func::domain_pic($pics[0]);
        }
        if($keyword){
            $user = ['id' => 0];
            if($token){
                $api = new Api();
                $jwt = $api->getJWT($token);
                if($jwt){
                    $user = Db::table('yw_user')->where('ucenterid', $jwt->id)->find();
                }
            }
            $hot = Db::table('yw_keywords')->where('keyword', $keyword)->order('num','desc')->find();
            if($hot){
                Db::table('yw_keywords')->where('id', $hot['id'])->setInc('num');
                if($hot['uid'] != $user['id']){
                    $history = Db::table('yw_keywords')->where(['uid'=>$user['id'],'keyword'=>$keyword])->find();
                    if(!$history){
                        Db::table('yw_keywords')->insert(['uid'=>$user['id'],'keyword'=>$keyword,'addtime'=>time()]);
                    }
                }
            }else{
                Db::table('yw_keywords')->insert(['uid'=>$user['id'],'keyword'=>$keyword,'addtime'=>time()]);
            }
        }
        return json(array('code' => 200, 'data' => $goods , 'msg'=>''));
    }

    public function create()
    {
        $token = request()->param('token');
        $keyword = ['hot'=>[],'history'=>[]];
        $keyword['hot'] = Db::table('yw_keywords')->limit(10)->order('num','desc')->column('keyword');
        if($token){
            $api = new Api();
            $jwt = $api->getJWT($token);
            if($jwt){
                $user = Db::table('yw_user')->where('ucenterid', $jwt->id)->find();
                if($user){
                    $keyword['history'] = Db::table('yw_keywords')->where(['uid'=>$user['id']])->limit(10)->column('keyword');
                }
            }
        }
        return json(array('code' => 200, 'data' => $keyword , 'msg'=>''));
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
        
    }
}
