<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28
 * Time: 9:14
 */
namespace app\admin\controller;

use app\admin\model\Userinfo;
use app\admin\controller\Admin;
use app\admin\model\Order as OrderModel;
use app\admin\model\Pay;
use app\notice\controller\Alipay;
use think\Db;
//include('Alipay.php');
class Refund extends Admin
{
    public function refundlist()
    {
        $keyword = input('get.wk');
        $state = input('get.state');
        if($state) {
            $where[] = ['refund', '=', $state];
        }else{
            $where[] = ['refund', 'in', [1,2,3]];
        }
        if($keyword != '') {
            $where[] = ['number', 'like', '%$keyword%'];
        }
        $ordlist = OrderModel::where($where)->order('create_time','desc')->paginate(10);
//        print_r($ordlist);exit;
        $page = $ordlist->render();
        $this->assign('ordlist',$ordlist);
        $this->assign('page',$page);
        $this->assign('wk',$keyword);
        return $this->fetch();
    }

    //搜索
    public function search()
    {
        $keyword = input('get.wk');
        $state = input('get.state');
        //dump($keyword);dump($issure);dump($state);exit;
        if ($keyword == '' && $state == '') {
            $ordlist = OrderModel::where('refund','in','1,2,3')->order('id','asc')->paginate(10);
        } else {
            $ordlist = OrderModel::where('ordercode','like',"%$keyword%")
                ->where('refund','like',"%$state%")
                //->where('ispay','1')
                ->where('refund','in','1,2,3')
                ->order('id','asc')->paginate(10);
        }
        $page = $ordlist->render();

        $this->assign('ordlist',$ordlist);
        $this->assign('page',$page);
        $this->assign('types','');
        $this->assign('wk','');
        $this->assign('members','');
        $this->assign('currentPage','');
        return $this->fetch('refundlist');
    }

    //订单详情,查看页面
    public function refunddetail()
    {
        $id = input('get.id');
        //dump($id);exit;
        if($id) {
            $order = OrderModel::get($id);
            //下单人信息
            $order_user = $order->orderuser;
            //订单商品信息
            $order_product = $order->orderproducts;
            //订单收货信息
            $order_shouhuo = $order->shouhuo;
            //订单操作记录
            $order_log = $order->paylog;
            $payment = bcsub(bcadd(bcadd($order->order_price,$order->logpay,2),$order->dispay,2),$order->user_money,2);
            //订单总额 物品+运费
            $orderpayment = bcadd($order->order_price ,$order->logpay,2);
            //dump($order_log);exit;
            $this->assign('order',$order);
            $this->assign('payment',$payment);
            $this->assign('orderpayment',$orderpayment);
            $this->assign('order_user',$order_user);
            $this->assign('order_product',$order_product);
            $this->assign('order_shouhuo',$order_shouhuo);
            $this->assign('order_log',$order_log);
        }
        return $this->fetch();

    }

    //同意
    public function oneagree(){
        $id = input("post.id/d");
        $reason = input("post.reason");
        $data = OrderModel::where('id',$id)->find();
        $uid = $data['uid'];
        if($data['refund'] !='' && $data['refund']!=2){

            $pay = new Pay;
            $order_id = $data['ordercode'];
            $flow = $pay->where('order_id',$order_id)->find();
            $flow_code = $flow['flow_code'];
//            $alipay = new app\notice\controller\Alipay();
            $alipay = new Alipay();
            $pay = $alipay->orderSearch($flow_code);
            $pay = json_decode($pay,true);
            if($pay['msg'] == 'Success'){
                $list['refund'] = 2;
                $list['reason'] = $reason;
                $refund = OrderModel::where('id',$id)->update($list);
                if($refund) {
                    $user = new Userinfo;
                    $userdata = $user->where('id',$uid)->find();
                    $money['money'] = $userdata['money'] + $data['payment'];
                    $moneys = $user->where('id',$uid)->update($money);
                    $refund = "已同意";
                }else{
                    $refund = "确认失败";
                }
            }else{
                $refund = "虚拟付款";
            }

        }else{
            $refund = "已同意，无需再确认";
        }
        return $refund;
    }

    //不同意
    public function onenoagree(){
        $id = input("post.id/d");
        $reason = input("post.reason");
        $data = OrderModel::where('id',$id)->find();
        if($data['refund'] !='' && $data['refund']!=3){
            $list['refund'] = 3;
            $list['reason'] = $reason;
            $refund = OrderModel::where('id',$id)->update($list);
            if($refund) {
                $refund = "已拒绝";
            }else{
                $refund = "拒绝失败";
            }
        }else{
            $refund = "已拒绝，无需再确认";
        }
        return $refund;
    }


}