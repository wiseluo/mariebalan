<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use app\admin\model\Shoppdtype;
use app\admin\model\Shoppd;
use think\Db;

class Shoppdtypes extends Admin
{
	public function index()
	{
		$oneCat = Db::table('yw_category')->order(['sort'=>'asc'])->select()->toArray();//获取一级分类
		//$twoCat = Db::table('yw_category')->where('adduid',$this->_U['id'])->where('fid','>',0)->order(['sort'=>'asc'])->select()->toArray();//获取二级分类
		$this->assign('oneCat',$oneCat);
		//$this->assign('twoCat',$twoCat);
        return $this->fetch();
	}

	//查看产品类型信息
	public function readPdTy()
	{
		$id = input('get.id');

		$info = Db::table('yw_category')->where('id',$id)->find();
		$info['pic'] = '/uploads/type/'.$info['pic'];
		$pdType = Db::table('yw_category')->where('state',1)->select()->toArray();
		$this->assign('info',$info);
		$this->assign('pdtype',$pdType);
		$this->view->engine->layout(false);
		return $this->fetch('readpdty');
	}

	//添加产品类型
	public function add()
    {
		$info=input('post.info/a');
		$info['adduid'] = $this->_U['id'];
		$info['addname'] = $this->_U['username'];
		$info['addtime'] = time();
		//$ptm = new Shoppdtype();
		Db::table('yw_category')->insert($info);
		$this->rec['msg'] = "添加成功";
        return $this->rec;
    }

    //更新产品类型
	public function update()
    {
		$id=input('get.id');
		$info=input('post.info/a');
		if (!empty($info['pic']) && (strpos($info['pic'],'/uploads/type/') !== false)){
            $info['pic'] = substr($info['pic'],14);
        }
		Db::table('yw_category')->where('id',$id)->update($info);
		$this->rec['msg'] = "修改成功";
        return $this->rec;
    }

    //单个删除
	public function delete()
    {
		$id = input('get.id');
		$id = (int)$id;
		Db::table('yw_category')->where('id',$id)->delete();
		$this->rec['msg'] = "删除成功";
        return $this->rec;
    }

    //批量删除
	public function deletes()
    {
		$ids = input('post.IDS/a');
		$arr = Shoppdtype::all();
		$id_arr = [];
		foreach ($ids as $k => $v) {
			$id_arr = array_merge($id_arr,getAllSubIds($arr, $v));//获取指定分类所有的后代分类id，包括它自己
		}
		if (Shoppd::get(['tid'=>['in',$id_arr]])) {
			$this->rec['status'] = 1;
			$this->rec['msg'] = "要删除的类型下有商品，无法进行删除，请更改相关商品的类型后再操作";
		} else {
			Shoppdtype::where('id','in',$ids)->delete();
			$this->rec['msg'] = "删除成功";
		}
        return $this->rec;
    }

    //显示
	public function makeFlag()
    {
		$ids=input('post.IDS/a');		
		if (is_array($ids)) Db::table('yw_category')->where('id','in',$ids)->update(array('state'=>1));
		$this->rec['msg'] = "操作成功";
        return $this->rec;
    }
	
	//取消显示
	public function canelFlag()
    {
		$ids=input('post.IDS/a');		
		if (is_array($ids)) Db::table('yw_category')->where('id','in',$ids)->update(array('state'=>0));
		$this->rec['msg'] = "操作成功";
        return $this->rec;
    }
}