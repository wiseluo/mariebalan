<?php

namespace app\api\controller;

use think\Db;
use app\api\controller\Token;
use app\api\model\Func;

//已废弃
class Orders extends Token
{
    public function index()
    {
        //1待付款,2已付款,待发货,3待收货,已发货,4已收货,完成,5关闭
        $pageIndex = request()->param('pageIndex',1);
        $pageSize = request()->param('pageSize',10);
        $state = request()->param('state',0); // 1待付款,2已付款,待发货,3待收货,已发货,4已收货,完成,5关闭
        $comment = request()->param('comment',0); // 0不可评价,1未评,2已评,3关闭评论
        $refund = request()->param('refund',0); // 售后:0正常,1退换处理中,2已完成退换,3未完成退换

        $where[] = ['uid', '=', $this->user['id']];
        if($refund) {
            $where[] = ['refund', '=', $refund];
        }
        if($comment) {
            $where[] = ['comment', '=', $comment];
        }
        if($state) {
            $where[] = ['state', '=', $state];
        }

        $order = Db::table('yw_orders')->field('uid',true)->where($where)->whereNull('delete_time')->order('id','desc')->page($pageIndex,$pageSize)->select()->toArray();
        foreach ($order as $key => $value) {
            //$order[$key]['express'] = $this->express[$value['expressid']];
            $order[$key]['goods'] = Db::table('yw_orders_goods')->where('oid',$value['id'])->select()->toArray();
        }
        return json(array('code' => 200, 'data' => $order , 'msg'=>''));
    }

    public function create()
    {
        //$addid = request()->param('address');
        try {
            $data = json_decode(request()->param('data'),true);
        } catch (\Exception $e) {
            $data = request()->param('');
            $data = $data['data'];
        }
        $goods = array();
        $money = 0.00;
        $tax = 0.00;
        $weight = 0;
        foreach ($data as $key => $value) {
            $temp = Db::table('yw_goods')->field('content',true)->where('id',$value['gid'])->find();
            if($value['pid'] > 0){
                $attrs_price = Db::table('yw_attrs_price')->where('id',$value['pid'])->find();
                $attrs = Db::table('yw_attrs')->whereIn('id',$attrs_price['aids'])->column('avalue');
                $temp['attrs'] = implode(' ',$attrs);
                $temp['price'] = $attrs_price['price'];
                $temp['stock'] = $attrs_price['stock'];
            }
            $temp['pid'] = $value['pid'];
            $temp['num'] = $value['num'];
            //$temp['tax'] = bcmul(bcmul($temp['price'],$temp['tariff'],2),$temp['num'],2);
            $money = bcadd($money,bcmul($temp['price'],$temp['num'],2),2);
            //$tax = bcadd($tax,$temp['tax'],2);
            $weight += $temp['weight']*$temp['num'];
            $temp['scid'] = $value['scid'];
            array_push($goods, $temp);
        }
        //$address = Db::table('yw_address')->where('id',$addid)->find();
        //$logistics = Func::logistics($address,$money,$weight);
        //$sum = bcadd(bcadd($money,$logistics,2),$tax,2);
        return json(array('code' => 200, 'data' => ['goods'=>Func::goods_info($goods,true),'sum'=>$money] , 'msg'=>''));
    }

    public function save()
    {
        $addid = request()->param('address');
        try {
            $data = json_decode(request()->param('data'),true);
        } catch (\Exception $e) {
            $data = request()->param('');
            $data = $data['data'];
        }

        $goods = array();
        $orders_goods = array();
        $money = 0.00;
        $tax = 0.00;
        $weight = 0;
        $content = '';
        // 推广员id
        //$from_ucenterid = 0;
        foreach ($data as $key => $value) {
            // if($value['from_ucenterid'] > 0){
            //     $from_ucenterid = $value['from_ucenterid'];
            // }
            if($value['pid'] > 0){
                $goods_info = Db::table('yw_attrs_price a')->join('yw_goods b','a.gid=b.id')->field('a.*,a.id as pid,b.title,b.weight,b.pics,b.state,b.tariff')->where('a.id',$value['pid'])->find();
            }else{
                $goods_info = Db::table('yw_goods a')->join('yw_attrs_price b','a.id=b.gid')->field('b.*,b.id as pid,a.title,a.weight,a.pics,a.state,a.tariff')->where('a.id',$value['gid'])->find();
            }
            if(!$goods_info)return json(array('code' => 401, 'data' => '' , 'msg'=>'商品不存在'));
            $goods_info = Func::goods_info($goods_info);
            $attrs = Db::table('yw_attrs')->whereIn('id',$goods_info['aids'])->column('avalue');
            if($attrs){
                $goods_info['attrs'] = implode(' ',$attrs);
            }else{
                $goods_info['attrs'] = '';
            }
            if($goods_info['state'] == 0)return json(array('code' => 401, 'data' => '' , 'msg'=>$goods_info['title'].' 已下架'));
            $goods_info['num'] = $value['num'];
            if($goods_info['num'] > $goods_info['stock']){ //库存不足
                return json(array('code' => 401, 'data' => '' , 'msg'=>$goods_info['title'].' 库存不足'.$goods_info['num'].'件'));
            }
            Db::table('yw_attrs_price')->where('id',$goods_info['pid'])->setDec('stock',$goods_info['num']);//减库存 多规格
            $content .= $goods_info['title'].' x'.$goods_info['num'];
            //$goods_info['tax'] = bcmul(bcmul($goods_info['price'],$goods_info['tariff'],2),$goods_info['num'],2);
            $money = bcadd($money,bcmul($goods_info['price'],$goods_info['num'],2),2);
            //$tax = bcadd($tax,$goods_info['tax'],2);
            $weight += $goods_info['weight']*$goods_info['num'];
            array_push($goods, ['sku'=>$goods_info['sku'],'gid'=>$value['gid'],'title'=>$goods_info['title'],'pic'=>$goods_info['pics'][0],'price'=>$goods_info['price'],'num'=>$goods_info['num'],'attrs'=>$goods_info['attrs']]);
            Db::table('yw_shopcars')->where(['uid'=>$this->user['id'],'id'=>$value['scid']])->delete();//删除购物车商品
        }
        if(!$goods){
            return json(array('code' => 401, 'data' => '' , 'msg'=>'请选择正确的商品.'));
        }
        $address = Db::table('yw_address')->where('id',$addid)->find();
        //$logistics = Func::logistics($address,$money,$weight);
        $order['number'] = 'SX'.date('YmdHis').str_pad(explode('.',microtime(true))[1],4,'0');//订单号
        $order['uid'] = $this->user['id'];
        $order['nickname'] = $this->user['nickname'];
        $order['money'] = $money;
        $order['content'] = $content;
        //$order['logistics'] = $logistics;
        //$order['tax'] = $tax;
        $order['payment'] = $money;//money+logistics+tax-discount
        $order['addtime'] = $order['uptime'] = time();
        $order['create_time'] = date('Y-m-d H:i:s', time());
        //$order['from_ucenterid'] = $from_ucenterid;
        // var_dump($data);
        // var_dump($order);exit;
        $order['id'] = Db::table('yw_orders')->insertGetId($order);

        foreach ($goods as $key => &$value) {
            $value['uid'] = $order['uid'];
            $value['oid'] = $order['id'];
        }
        Db::table('yw_orders_goods')->insertAll($goods);

        $address = Func::addid2txt($address);
        $address['oid'] = $order['id'];
        Db::table('yw_orders_address')->strict(false)->insert($address);
        //http://wx.sumsoar.com/api.php/wechat/index/postMsg/msg/meet/openid/oAgjhwugovg7NNMtQ-yjTCXR6SV0/1.html
        //$omspush = Oms::pushOrder(['order'=>$order,'goods'=>$goods,'address'=>$address,'user'=>$this->user]);//订单推送到OMS
        return json(array('code' => 200, 'data' => $order['id'], 'msg'=>''));
    }

  //   public function read($id)
  //   {
  //       $order = Db::table('yw_orders')->field('uid,isdel',true)->where(['uid'=>$this->user['id'],'isdel'=>0,'id'=>$id])->find();
  //       $order['goods'] = Db::table('yw_orders_goods')->where('oid',$order['id'])->select()->toArray();
  //       $order['address'] = Db::table('yw_orders_address')->where('oid',$order['id'])->find();
  //       //$order['express'] = $this->express[$order['expressid']];
		// //$userid = Db::table('yw_goods')->where('id',$order['goods'][0]['gid'])->value('userid');
		// //$order['tel'] = Db::table('yw_user')->where('id',$userid)->value('tel');
  //       return json(array('code' => 200, 'data' => $order , 'msg'=>''));
  //   }


    public function edit($id)
    {
        
    }

    // public function update($id)
    // {
    //     $state = request()->param('state');
    //     $token = request()->param('token');
    //     //1（取消订单）2（提醒发货）3（确定收货）
    //     //1（待付款）2（已付款,待发货）3（待收货,已发货）4（已收货,完成）5(关闭)
    //     $order = Db::table('yw_orders')->where(['id'=>$id,'uid'=>$this->user['id']])->find();
    //     if(!$order) {return json(array('code' => 401, 'data' => '', 'msg'=>'订单不存在!'));}
    //     if($state == 1){//取消订单
    //         if($order['state'] == 1){
    //             $return = Db::table('yw_orders')->where('id',$id)->update(['state'=>5]);
    //             return json(array('code' => 200, 'data' => $return, 'msg'=>'订单取消成功!'));
    //         }//else if($order['state'] == 2){
    //         //退钱 改订单
    //         //$return = Db::table('yw_orders')->where('id',$id)->update(['state'=>5]);
    //         //return json(array('code' => 200, 'data' => $return, 'msg'=>''));
    //         //}
    //     }else if($state == 2){//提醒发货
    //         if($order['addtime'] == $order['uptime'] || ($order['uptime'] + 86400) < time()){
    //             Db::table('yw_orders')->where('id',$order['id'])->update(['uptime'=>time(),'reminder'=>2]);
    //             //$gids = Db::table('yw_orders_goods')->where('oid',$order['id'])->select();
				// //$ordnumber = $order['number'];
    //             //foreach ($gids as $k=>$v){
    //                // $usercenterids[$k] = Db::table('yw_goods')->where('id',$v['gid'])->find();
    //                 // $postTsData=array(
    //                 //     'token'=>$token,
    //                 //     'usercenterid'=>92617,
    //                 //     'title'=>'提醒发货通知',
    //                 //     'message'=>'订单号为'.$order['number'].'请尽快发货',
    //                 //     'publish_title'=>'提醒发货通知',
    //                 //     'classify'=>'发货'
    //                 // );
    //                 //$send = new SendData();
    //                 //$port = $send->http_post_data(Config::get('ENV_CONFIG')."/messageslist",json_encode($postTsData, JSON_UNESCAPED_UNICODE));
				// 	//$goodId = Db::table('yw_orders_goods')->where('oid',$order['id'])->value('GROUP_CONCAT(gid)');
				// 	//$uid = Db::table('yw_goods')->where('id','in',$goodId)->value('GROUP_CONCAT(distinct uid)');
    //                 //Http::get('http://localhost:808/getuserinfos/'.$uid.'?token='.$token.'&msg=订单编号【'.$ordnumber.'】已催单，请及时安排发货&order='.$order['id']);
    //             //}
    //             return json(array('code' => 200, 'data' => '提醒成功', 'msg'=>'提醒成功'));
    //         }else{
    //             return json(array('code' => 200, 'data' => '已提醒成功,请明天再试!', 'msg'=>'已提醒成功,请明天再试!'));
    //         }
    //     }else if($state == 3){//确定收货
    //         if($order['state'] == 3) {
    //             $return = Db::table('yw_orders')->where('id',$id)->update(['state'=>4,'comment'=>1]);
    //             Db::table('yw_orders_goods')->where('oid',$id)->update(['comment'=>1]);//可评价
    //             return json(array('code' => 200, 'data' => $return, 'msg'=>''));
    //         }
    //     }
    //     return json(array('code' => 401, 'data' => $return, 'msg'=>'操作流程错误!'));
    // }

    // public function delete($id)
    // {
    //     $return = Db::table('yw_orders')->where(['state'=>5,'id'=>$id,'uid'=>$this->user['id']])->data('isdel',1)->update();
    //     return json(array('code' => 200, 'data' => $return, 'msg'=>''));
    // }
}
