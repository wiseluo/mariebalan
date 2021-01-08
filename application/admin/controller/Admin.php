<?php
// +----------------------------------------------------------------------
// | SentCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Module;
use app\admin\model\Admin as AdminModel;
use think\Db;
use think\facade\Session;

class Admin extends Controller {
	var $_U = array();//['id'=>2,'name'=>'mojin','username'=>'mojin','pic'=>'','ip'=>'','lastadds'=>'','sex'=>0,'logins'=>10,'lasttime'=>'2017-10-22']
	var $_G = array('sitename'=>'跨境洋货','siteurl'=>'dfdf','uploadfile'=>'/uploads','ip'=>'','sex'=>['女','男'],'act'=>['不推荐','推荐']);
	public $rec = array('msg'=>'','data'=>array(),'status'=>0);
	function initialize() {
		$this->checkLogin(); //检查用户是否已登录
		$this->getLoginAdmin(); //获取登录用户的详细信息，并将其保存到 $_U 中
		
		$marr=array();
		$menu=new Module();
		$sidebar=$menu->where('state',1)->order('sort','asc')->select()->toArray();
		$orderSum = Db::table('yw_order')
			->where('state',2)
			->count();
		if(is_array($sidebar))
		foreach($sidebar as $rs){
			if($rs['fid']>0){
				if(empty($marr[$rs['fid']]))$marr[$rs['fid']]=0;
				$marr[$rs['fid']]++;
			}
		}
		//菜单初始化
		//if($this->_U['id']==2)dump($_COOKIE);
		$_C=array();
		if(empty($_COOKIE['menuone'])){
			//$_C[$_COOKIE['menuone']]='';
		}else{
			$_C[$_COOKIE['menuone']]='active';
		}
		if(empty($_COOKIE['menutwo'])){
			//$_C[$_COOKIE['menutwo']]='';
		}else{
			$_C[$_COOKIE['menutwo']]='active';
		}
		if(empty($_COOKIE['menuthree'])){
			//$_C[$_COOKIE['menuthree']]='';
		}else{
			$_C[$_COOKIE['menuthree']]='active';
		}
		$_C[0]='';
		if(empty($_COOKIE['menuone']))$_C[0]='active';
		//$this->view->engine->layout('index/home');
		//注入菜单信息
		$this->assign('orderSum', $orderSum);
		$this->assign('_C', $_C);
		$this->assign('_U', $this->_U);
		$this->assign('_G', $this->_G);
		$this->assign('sidebar',$sidebar);
		$this->assign('marr',$marr);
		$this->assign('_COOKIE',$_COOKIE);
	}

	//验证用户登录
	public function checkLogin() {
		//使用session来判断
		$admin = Session::get('admin');
		if ($admin && $admin->id) {
			return true;
		}
		return $this->redirect('/admin/login','请先登录后操作');	
	}

	//获取登录用户的详细信息，并将其保存到 $_U 中
	public function getLoginAdmin() {
		//获取session
		$admin = Session::get('admin');
		//dump($admin);
		$admin = AdminModel::get($admin->id);
		$userPic = null;
		// if ($user->pic) {
		// 	$userPicArr = disposeFile($user->pic);
		// 	$userPic = $userPicArr[0];
		// }
		$this->_U = [
			'id'=>$admin->id,
			'username'=>$admin->username,
		];
	}

}
