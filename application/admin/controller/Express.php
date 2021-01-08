<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use think\Db;

class Express extends Admin{
	public function index(){
		$wk = request()->param('wk');
		$express = DB::table('yw_dispatchs')->where(
			function ($query) use ($wk){
				if($wk){
					$query->where('dispatchname','like',"%$wk%");
				}
			}
		)->order('id','desc')->paginate(10);
		$this->assign('pages',$express);
		$this->assign('wk','');
		$this->assign('express',$express);
		return $this->fetch();
	}

	public function edit(){
		$id = request()->param('id');
		if(request()->isPost()){
			$info = request()->post('info/a');
			$areas = request()->post('areas/a');
			$info['areas'] = serialize($areas);
			if(isset($info['isdefault'])){
				DB::table('yw_dispatchs')->where('isdefault',1)->update(['isdefault'=>0]);
			}
			if($id){
				DB::table('yw_dispatchs')->where('id',$id)->update($info);
			}else{
				DB::table('yw_dispatchs')->insert($info);
			}
			$this->success('提交成功');
		}else{
			$info = DB::table('yw_dispatchs')->where('id',$id)->find();
			$areas = unserialize($info['areas']);
			$this->assign('areas',$areas);
			$this->assign('info',$info);
			return $this->fetch();
		}
	}

}