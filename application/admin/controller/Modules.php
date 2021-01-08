<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use app\admin\model\Module;
use think\Db;
class Modules extends Admin
{
    //列表信息
	public function index()
    {
		$dbs=Module::order('id','desc')->paginate(15);
		$page = $dbs->render();
		$this->assign('dbs',$dbs);
		$this->assign('page',$page);
        return $this->fetch();
    }
	
	//更新记录
	public function update()
    {
		$id=input('get.id');
		$info=input('post.info/a');
		Module::where('id',$id)->update($info);
        return $this->rec;
    }
	
	//添加记录
	public function add()
    {
		$info=input('post.info/a');
		$mue=new Module();
		$mue->allowField(true)->save($info);
        return $this->rec;
    }
	
	//读取记录
	public function read()
    {
		$id=input('get.id');
		$info=Module::where('id',$id)->find();
		$this->assign('info',$info);
		$this->view->engine->layout(false);
        return $this->fetch('detail');
    }
	
	//单个删除
	public function delete()
    {
		$id=input('get.id');
		$id=(int)$id;
		$info=Module::where('id',$id)->delete();
        return $this->rec;
    }
	
	//批量删除
	public function deletes()
    {
		$ids=input('post.IDS/a');
		
		if(is_array($ids))$info=Module::where('id','in',$ids)->delete();
		
        return $this->rec;
    }
	
	//批量审核
	public function modFlag()
    {
		$ids=input('post.IDS/a');
		
		if(is_array($ids))$info=Module::where('id','in',$ids)->update(array('state'=>1));
		
        return $this->rec;
    }
	
	//取消审核
	public function canelFlag()
    {
		$ids=input('post.IDS/a');
		
		if(is_array($ids))$info=Module::where('id','in',$ids)->update(array('state'=>0));
		
        return $this->rec;
    }

    //查询
    public function search()
    {
    	$keyword = input('get.wk');
		if ($keyword == '') {
			$dbs = Module::order('id','desc')->paginate(15);
			$page = $dbs->render();
		} else {
			$dbs = Module::where('name','like',"%$keyword%")
					->order('id','desc')
					->paginate(15,false,[
					    'query' => ['wk'=>"$keyword"]
					]);
	    	$page = $dbs->render();
	    }
    	$this->assign('wk',$keyword);
		$this->assign('dbs',$dbs);
		$this->assign('page',$page);
        return $this->fetch('index');
    }

}
