<?php

namespace app\api\controller;

use think\Db;
use think\Controller;
use app\api\model\Func;
use app\admin\model\Goods as Goodpds;

class Goods extends Controller
{
    public function index()
    {
        //$pageIndex = request()->param('pageIndex',1);
        $pageSize = request()->param('pageSize', 10);
        $by = request()->param('by', 'id');
        $sort = request()->param('sort', 'desc');
        $region = request()->param('region', '');
        $tid = request()->param('tid', 0);
        $keyword = request()->param('keyword', '');

        // $goods = Db::table('yw_goods')->where(
        //     function ($query) use($keyword,$tid) {
        //         if($tid > 0){
        //             $tids = Db::table('yw_types')->where('fid',$tid)->column('id');
        //             if($tids){
        //                 $query->whereIn('tid',implode(',',$tids));
        //             }else{
        //                 $query->where('tid',$tid);
        //             }
        //         }
        //         if($keyword){
        //             $query->where('title','like',"%$keyword%");
        //         }
        //     }
        // )->where('state',1)->order([$by=>$sort])->page($pageIndex,$pageSize)->select();

        $where[] = ['state', '=', 1];
        if($region) {
            $where[] = ['region', '=', $region];
        }
        if($tid) {
            $where[] = ['tid', '=', $tid];
        }
        if($keyword) {
            $where[] = ['title', 'LIKE', "%$keyword%"];
        }
        $goods = Goodpds::where($where)->order($by, $sort)->paginate($pageSize)->toArray();

        foreach ($goods['data'] as $key => $value) {
            if(isset($value['addtime'])){
                $goods['data'][$key]['adddate'] = date('Y-m-d',$value['addtime']);
            }
            $pics = explode('|', $value['pics']);
            $goods['data'][$key]['pics'] = common_func_domain() ."/uploads/products/". $pics[0];
        }
        return json(['code' => 200, 'data' => $goods, 'msg'=>'']);
    }

    public function create()
    {
        
    }

    public function save()
    {
        
    }

    public function read($id)
    {
        $token = request()->param('token');
        $goods = Db::table('yw_goods')->where(['id'=>$id,'state'=>1])->find();
        if(!$goods){
            return json(array('code' => 401, 'data' => '' , 'msg'=>'商品不存在,或已下架!'));
        }
        Db::table('yw_goods')->where('id',$id)->setInc('hits');
        $goods['attr'] = Db::table('yw_attrs')->where('gid',$id)->field('id,akey,avalue')->select()->toArray();
        if($goods['attr']){
            $attrs = array();
            foreach ($goods['attr'] as $key => $value) {
                $attrs[$value['akey']][] = $value;
            }
            $goods['attr'] = array();
            foreach ($attrs as $key => $value) {
                array_push($goods['attr'],['spec_kind'=>$key,'spec'=>$value]);
            }
        }
        $goods['attr_price'] = Db::table('yw_attrs_price')->where('gid',$id)->field('id,aids,price,stock')->select()->toArray();
        if($goods['attr_price']){
            $minmax = array();
            foreach($goods['attr_price'] as $key=>$value){
                array_push($minmax, $value['price']);
            }
            sort($minmax);
            $goods['minprice'] = $minmax[0];
            $goods['maxprice'] = $minmax[count($minmax)-1];
        }else{
            $goods['minprice'] = $goods['price'];
            $goods['maxprice'] = $goods['price'];
        }
        // $goods['merchant'] = '浙江阿贝得工贸有限公司';
        // $goods['bz'] = '消费者在满足7天无理由退换货申请条件的前提下，可以提出“7天无理由退换货”的申请!';
        // $goods['yf'] = '根据商品规格、重量、路程远近，运费以物流公司实际为准!';
        $goods['favorite'] = 0;
        // if($token){
        //     $api = new Api();
        //     $jwt = $api->getJWT($token);
        //     if($jwt){
        //         $user = Db::table('yw_users')->where('ucenterid', $jwt->id)->find();
        //         if($user){
        //             $goods['favorite'] = Db::table('yw_favorite')->where(['uid'=>$user['id'],'gid'=>$id])->count();
        //             $history = Db::table('yw_historys')->where(['uid'=>$user['id'],'gid'=>$id])->find();
        //             if($history){
        //                 Db::table('yw_historys')->where(['uid'=>$user['id'],'gid'=>$id])->data(['addtime'=>time()])->update();
        //             }else{
        //                 Db::table('yw_historys')->data(['uid'=>$user['id'],'gid'=>$id,'addtime'=>time()])->insert();
        //             }
                    
        //         }
        //     }
        // }
        return json(array('code' => 200, 'data' => Func::goods_info($goods) , 'msg'=>''));
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
