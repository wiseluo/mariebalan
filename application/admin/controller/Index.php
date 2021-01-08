<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use think\Db;
use think\facade\Cache;
use app\admin\model\User;
use app\admin\model\Order;
use app\admin\model\Module;   //菜单

class Index extends Admin
{
    public function index()
    {
        $limit = 5;   //显示记录数
    	$order = new Order;
    	$user = new User;
       // $ucenter = new Ucenter;
    	$count_user = $user->count(); //会员总数
    	$count_order = $order->count(); //订单总数
    	$count_unpaidorder = $order->where('state', 1)->count();//未付款订单总数
        $count_undeliverorder = $order->where('state', 2)->count();//待发货订单总数

    	$notshipped_order = $order->where('state', 1)->order('id','desc')->limit($limit)->select()->toArray();    //待付款 1

    	$audit_order = $order->where('state', 2)->order('id','desc')->limit($limit)->select()->toArray();		 //待发货订单 2
       
        $new_user = $user->order('create_time','desc')->limit($limit)->select()->toArray();                         //最新会员
        
    	$new_order = $order->order('create_time','desc')->limit($limit)->select()->toArray();					 //最新订单  
    	
    	$this->assign('count_user',$count_user);    	
    	$this->assign('count_order',$count_order);    	
    	$this->assign('count_unpaidorder',$count_unpaidorder);   
    	$this->assign('count_undeliverorder',$count_undeliverorder); 

    	$this->assign('notshipped_order',$notshipped_order);
    	$this->assign('audit_order',$audit_order);
        $this->assign('new_user',$new_user);
    	$this->assign('new_order',$new_order);
      
        return $this->fetch();
    }

    public function cache()
    {
        Cache::clear();
    }

    protected function arr_size($arr)
    {
        $showcount = 6;   //显示记录数量
        $arrlong = count($arr);   //数组实际长度
        //return $arrlong;
        if($arrlong >= $showcount){    
           
        }else{     //小于，数组添加空元素
            $cha = $showcount - $arrlong;
            for($i = 0;$i < $cha;$i++){
                $arr[] = '';
            }
        }
        return $arr;
    }

}
