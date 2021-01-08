<?php

namespace app\admin\controller;

use think\Request;
use app\admin\controller\Admin;
use app\admin\model\Order as OrderModel;
use app\admin\model\OrderGoods;
use app\admin\model\Shoppd;
use app\admin\model\Paylog;
use think\Db;

class Order extends Admin
{
	public function testpay()
	{
		$id = request()->param('id');
		$order = Db::table('yw_order')->where('id',$id)->find();
		$payno = request()->param('payno');
		$paytype = request()->param('paytype');
		if($payno && $order){
			$payinfo = ['payno'=>$payno,'paytime'=>time(),'state'=>2,'paytype'=>$paytype];
        	//$omsedit = Oms::editOrder(['number'=>$order['number'],'order'=>$payinfo]);//订单推送到OMS
        	$return = Db::table('yw_order')->where('id',$id)->update($payinfo);
        	$this->rec['msg'] = "支付成功";
        	return $this->rec;
		}
		$this->view->engine->layout(false);
	}

	public $states = ['','待付款','已付款,待发货','待收货,已发货','已收货,完成','关闭'];
	//订单信息，列表，主页面
	public function orderlist()
	{
        $keyword = input('get.wk');
        $issure = input('get.issure');
        $state = input('get.state');
        if ($keyword == '' && $issure == '' && $state == '') {
            $ordlist = Db::table('yw_order a')
                ->join('yw_user b','a.user_id = b.id', 'left')
                ->field('a.*,b.nickname')
                ->whereNull('a.delete_time')
                ->order('id','desc')->paginate(10);
        } else {
            $ordlist = Db::table('yw_order a')
                ->join('yw_user b','a.user_id = b.id', 'left')
                ->field('a.*,b.nickname')
                ->where(
                    function ($query) use($state) {
                    if($state > 0){
                        $query->where('a.state',$state);
                    }
                })
                ->where(
                    function ($query) use($keyword) {
                        if($keyword != ''){
                            $query->where('a.number','like',"%$keyword%");
                        }
                    }
                )
                ->where(
                    function ($query) use($issure) {
                        if($issure != ''){
                            $query->where( 'a.paytype',$issure);
                        }
                    }
                )
                ->whereNull('a.delete_time')
                ->order('id','desc')->paginate(10,false, ['query'=>['issure'=>$issure,'state'=>$state,'wk'=>$keyword]]);
        }
//		$ordlist = Db::table('yw_order a')
////			->join('yw_user b','a.user_id = b.id')
////			->field('a.*,b.nickname')
////			->where('a.isdel',0)
////			->order('id','desc')->paginate(10);
		$page = $ordlist->render();
		$this->assign('ordlist',$ordlist);
		$this->assign('states',$this->states);
		$this->assign('page',$page);
		$this->assign('wk',$keyword);
		$this->assign('issure',$issure);
		$this->assign('state',$state);
		return $this->fetch();
	}

	public function ordersend()
	{
		$ordlist = Db::table('yw_order a')
			->where('a.state',2)
            ->whereNull('a.delete_time')
			->join('yw_order_address b','a.id = b.order_id', 'left')
			->field('a.*,b.consignee,b.province,b.city,b.town,b.address,b.phone,b.zipcode')
			->order('a.id','desc')->paginate(10);
		$page = $ordlist->render();
		$this->assign('ordlist',$ordlist->toArray()['data']);
		$this->assign('states',$this->states);
		$this->assign('page',$page);
		return $this->fetch();
	}

	public function send(Request $request)
	{
		if($request->method() == 'GET') {
			$id = $request->param('id');
			$order = Db::table('yw_order')->where('id',$id)->find();
			$express_list = model('admin/ExpressRepository', 'repository')->select();
			$this->assign('order',$order);
			$this->assign('express', $express_list);
			$this->view->engine->layout(false);
			return $this->fetch('send');
		}else if($request->method() == 'POST') {
			$id = $request->param('id');
			$express = $request->param('express');
			$data = [
				'state'=>3,
				'express_name'=>$express['name'],
				'express_no'=>$express['no'],
			];
			$res = model('api/OrderRepository', 'repository')->update($data, ['id'=> $id]);
			if($res) {
				return json(['code'=> 200, 'msg'=> '修改成功']);
			}else{
				return json(['code'=> 200, 'msg'=> '修改失败']);
			}
		}
	}
	 //1130
    public function orderconfirmed()
	{
		$ordlist = OrderModel::where('state',3)->order('id','desc')->paginate(10);
		$this->assign('ordlist',$ordlist);
		$this->assign('page','');
		$this->assign('wk','');
		return $this->fetch();
	}
	//订单详情,查看页面
	public function orderdetails()
	{
		$id = input('get.id');
		$order = Db::table('yw_order')->where('id',$id)->find();
		if($order) {
			$order['user'] = Db::table('yw_user')->where('id',$order['user_id'])->find();
			//订单商品信息
			$order['goods'] = Db::table('yw_order_goods')->field('title,pic,price,num,attrs')->where('order_id',$order['id'])->select()->toArray();
			//订单收货信息
			$order['address'] = Db::table('yw_order_address')->where('order_id',$order['id'])->find();
			$this->assign('order',$order);
		}
		$this->assign('states',$this->states);
		return $this->fetch();
	}

	//添加修改页面
	public function addorupdate()
	{	
		//显示原订单信息
		$id = input('get.id');	
		if($id) {
			$order = OrderModel::get($id);
			//订单商品信息
			$order_product = $order->orderproducts;
			//订单收货信息
			$order_shouhuo = $order->shouhuo;
			$paytype = [0=>'现金支付',1=>'支付宝支付',2=>'微信支付'];
			$this->assign('paytype',$paytype);
			$this->assign('order_id',$id);
			$this->assign('order',$order);
			$this->assign('order_product',$order_product);
			$this->assign('order_shouhuo',$order_shouhuo);
		}
		return $this->fetch();
	}

	//添加/修改订单  --rizhi 
	public function addorder()
	{
		$id = input('get.id');
		$getordermess = input("post.");
		$ordermess = $getordermess['order'];
		$shouhuomess = $getordermess['shouhuo'];
		$productsmess = $getordermess['products'];
		//dump($id);
		//dump($ordermess);
		//dump($shouhuomess);
		//dump($productsmess['prosid']);
		//dump($productsmess['proscount']);
		//dump($productsmess['prosprice']);
		//exit;
		$arr_prosid = explode(',',$productsmess['prosid']);
		$arr_proscount = explode(',',$productsmess['proscount']);
		$arr_prosprice = explode(',',$productsmess['prosprice']);
		if($id) {      //修改订单
			$order = new OrderModel;
			$old_order = OrderModel::get($id);
			$oldlogistics = $old_order['logistics'];  	 //原物流方式
			$oldinvoice = $old_order['invoice'];		 //原发票抬头
			$oldorderpay = $old_order['orderpay'];

			$shouhuo = new Shmessage;
			$orderpros = new OrderGoods;
			$order->allowField(true)->save($ordermess,['id'=>$id]);
			$shouhuo->allowField(true)->save($shouhuomess,['order_id'=>$id]);
			//$order->allowField(true)->where('id',$id)->update($ordermess);
			//$shouhuo->allowField(true)->where('order_id',$id)->update($shouhuomess);
			//商品信息可以独立修改，放外面
			foreach($arr_prosid as $k=>$v) {
				//dump($arr_proscount[$k]);exit;
				$orderpros->allowField(true)->where('id',$v)->update(['num'=>$arr_proscount[$k],'price'=>$arr_prosprice[$k]]);
			}

			//同时加入到操作日志中
			$paylog = new Paylog;
			//查找出订单的各 价格 信息对比新数据
			
			$newlogistics = $ordermess['logistics'];	//新物流方式
			$newinvoice = $ordermess['invoice'];		//新发票抬头
			$neworderpay = $ordermess['orderpay']/100;      //新订单商品总价
			
			$nameid = $this->_U['id'];
			$name = $this->_U['name'];
			$username = $this->_U['username'];
			//dump($id);dump($nameid);dump($username);dump($name);exit;
			$content = '';
			if($oldlogistics != $newlogistics){
				$content .= '修改了物流方式(原方式：'.$oldlogistics.'  修改后方式：'.$newlogistics.')  ';
			}
			if($oldinvoice != $newinvoice){
				$content .= '修改了发票抬头(原发票抬头：'.$oldinvoice.'  修改后发票抬头：'.$newinvoice.')  ';
			}
			if($oldorderpay != $neworderpay){
				$content .= '修改了订单商品价格(原价格：'.$oldorderpay.'  修改后价格：'.$neworderpay.')  ';
			}
			$data['order_id'] = $id;
			$data['user_id'] = $data['ouid'] = $nameid;
			$data['name'] = $name;
			$data['username'] = $username;
			$data['content'] = $content;
			$data['addtime'] = time();   //增加时间
			if(!empty($content)){
				//dump($data);exit;
				$paylog->allowField(true)->save($data);
			}
		} 
		else {			//添加订单
			//订单信息
			/*$orderinfo = input("post.info");
			//订单商品信息
			$order_product = input('post.products');

			$order = new OrderModel;
			if($order->allowField(true)->save($orderinfo)) {
				$order->orderproduct()->allowField(true)->save($order_product);
			}*/
		}
	}

	//删除订单
	public function deleteorder()
	{
		$id = input('get.id');
		$order = OrderModel::get($id);
		if ($order) {
			//Db::table('yw_order')->where('id',$id)->update(['isdel'=>1]);
			$order->delete();
			return 1;
		}else{
			return 0;
		}
	}

	//商品列表
	public function sproducts()
	{
		$orderid = input('get.id');
		$prolist = Shoppd::field('id,title,price,stock,tid')->paginate(100);
		$page = $prolist->render();  
		//dump($prolist);exit;
		$this->assign('orderid',$orderid);
		$this->assign('prolist',$prolist);
		$this->assign('page',$page);
		$this->view->engine->layout(false);
		return $this->fetch('sproducts');
	}
	//商品列表,搜索后
	public function ssproducts()
	{
		$orderid = input('get.id');
		$keyword = input('get.kw');
		//dump($orderid);dump($keyword);exit;
		$prolist = Shoppd::field('id,title,price,stock,tid')->where('title','like',"%$keyword%")->paginate(100);
		$page = $prolist->render();  
		//dump($prolist);exit;
		$this->assign('orderid',$orderid);
		$this->assign('prolist',$prolist);
		$this->assign('page',$page);
		$this->view->engine->layout(false);
		return $this->fetch('sproducts');
	}

	//订单商品列表
	public function orderproducts()
	{
		return $this->fetch();
	}
	//修改选择商品，加入订单商品表中
	public function addtoorderpro()
	{
		$orderid = input('get.id');
		$prosid_arr = input('post.');
		$prosidstr = $prosid_arr['prosid_arr'];
		$prosidarr = explode(',',$prosidstr);

		$orderpro = new OrderGoods;

		$res = Shoppd::field('title,pic,price')->whereIn('id',$prosidstr)->select()->toArray();
		foreach($res as $k=>$v) {     //新增商品给对应的订单
			$vv[$k]['title'] = $v['title'];
			$vv[$k]['pic'] = $v['pic'];
			$vv[$k]['price'] = $v['price'];    
			//$vv[$k]['pdsize'] = $v['pdsize'];
			$vv[$k]['order_id'] = $orderid;
		}
		//dump($vv);exit;
		$orderpro->allowField(true)->saveAll($vv);
	}
	//删除订单中的商品
	public function removeorderpro()
	{
		$id = input('get.id');
		$orderpro = OrderGoods::get($id);
		if ($orderpro) {
			$orderpro->delete();
		}
	}

	//订单商品价格修改
	public function updateprice()
	{
		$orderid = input('get.id');
		$order = Db::table('yw_order')->where('id',$orderid)->find();
		$this->assign('order',$order);
		$this->view->engine->layout(false);
		return $this->fetch('updateprice');
	}
	//订单价格修改 --rizhi 
	public function updatemoney()
	{
		$orderid = input('get.id');
		$orderdata = input('post.');
		//$orderdata['payment'] = 
		Db::table('yw_order')->where('id',$orderid)->update($orderdata);
		exit;
		//
		//dump($orderid);dump($orderdata);exit;
		$old_order = OrderModel::get($orderid);
		$oldlogpay = $old_order['logpay'];  	 //原物流费
		$olddispay = $old_order['dispay'];		 //原价格调整

		//dump($orderdata['order_logpay']);exit;
		//计算订单的各个 费用
		$user_money = 100*$old_order->usermoney;
		$order_logpay = $orderdata['logpay'] = $orderdata['order_logpay'];   //新物流费
		$order_dispay = $orderdata['dispay'] = $orderdata['order_dispay'];   //新价格调整
		$order_orderpay = $orderdata['orderpay'] = 100*$old_order['orderpay'];
		$orderdata['payment'] = bcsub(bcadd(bcadd($order_logpay,$order_dispay,2) , $order_orderpay,2), $user_money,2);
		//dump($orderdata['payment']);exit;
		//22222222222222222222222222222222222222222222222222
		$order = new OrderModel;
		$order->allowField(true)->save($orderdata,['id'=>$orderid]);
		
		//同时加入到操作日志中
		$paylog = new Paylog;
		//查找出订单的各 价格 信息对比新数据
		
		$newlogpay = $orderdata['logpay']/100;		//新物流费
		$newdispay = $orderdata['dispay']/100;		//新价格调整

		$nameid = $this->_U['id'];
		$name = $this->_U['name'];
		$username = $this->_U['username'];
		$content = '';
		if($oldlogpay != $newlogpay){
			$content .= '修改了物流费用(原物流费用：'.$oldlogpay.'  修改后物流费用：'.$newlogpay.')  ';
		}
		if($olddispay != $newdispay){
			$content .= '调整了价格(原价格：'.$olddispay.'  修改后价格：'.$newdispay.')  ';
		}

		$data['order_id'] = $orderid;
		$data['user_id'] = $data['ouid'] = $nameid;
		$data['name'] = $name;
		$data['username'] = $username;
		$data['content'] = $content;
		$data['addtime'] = time();   //增加时间
		if(!empty($content)){
			//dump($data);exit;
			$paylog->allowField(true)->save($data);
		}
	}




    //待确认搜索
    public function daisearch()
    {
        $keyword = input('get.wk');
        if ($keyword == '') {
            $ordlist = OrderModel::order('id','asc')->paginate(10);
        } else {
            $ordlist = OrderModel::where('ordercode','like',"%$keyword%")
		            ->order('id','asc')->paginate(10);
        }       
        $page = $ordlist->render();
         
        $this->assign('ordlist',$ordlist);
		$this->assign('page',$page);
		$this->assign('types','');
		$this->assign('wk','');
		$this->assign('members','');
		$this->assign('currentPage','');
        return $this->fetch('orderconfirmed');
    }




    //批量删除
    public function dels()
    {
        $ids=input('post.IDS/a');
        if(is_array($ids))$info=OrderModel::where('id','in',$ids)->delete();
        
        return $this->rec;
    }
    //批量确认
    public function sure()
    {
    	$ids=input('post.IDS/a');
        if(is_array($ids))$info=OrderModel::where('id','in',$ids)->update(array('issure'=>1));
        
        return $this->rec;
    }

}