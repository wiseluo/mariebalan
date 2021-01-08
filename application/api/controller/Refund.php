<?php

namespace app\api\controller;

use think\Db;
use think\Controller;
use app\api\controller\Token;
use app\api\model\Func;

class Refund extends Token
{
    public function index()
    {
        $pageIndex = request()->param('pageIndex',1);
        $pageSize = request()->param('pageSize',10);
        // $refund = request()->param('refund',0);
        // $state = request()->param('state',0);
        $goods = Db::table('yw_orders_goods')->where('refund','>',0)->where(['uid'=>$this->user['id']])->page($pageIndex,$pageSize)->select();
        return json(array('code' => 200, 'data' => $goods, 'msg'=>''));
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
        $refund = request()->param('refund',0);
        $refundreason = request()->param('refundreason');
        $refundpics = request()->param('refundpics');
        $og = Db::table('yw_orders_goods')->where(['uid'=>$this->user['id'],'id'=>$id])->find();
        if($og){
            $orders = Db::table('yw_orders')->where(['uid'=>$this->user['id'],'id'=>$og['oid']])->where('state','>',1)->where('state','<',5)->find();
            if(!$orders) return json(array('code' => 401, 'data' => '', 'msg'=>'该订单无法进行售后!'));
            $return = Db::table('yw_orders')->where('id',$og['oid'])->update(['refund'=>1]);
            $return = Db::table('yw_orders_goods')
                ->where('id',$og['id'])
                ->update([
                    'state'=>1,
                    'refund'=>$refund,
                    'refundreason'=>$refundreason,
                    'refundpics'=>Func::md5_pic($refundpics),
                    'refundtime'=>time()
                ]);
        }
        return json(array('code' => 200, 'data' => $return, 'msg'=>''));
    }

    public function delete($id)
    {
        
    }
}
