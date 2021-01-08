<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use app\admin\model\Shoppdtype;
use app\admin\model\Attribute as Attr;

class Attribute extends Admin
{
	//产品属性列表
	public function index()
	{
		$attr = new Attr();
		$dbs = $attr->getAttrList();
		$listRows = $dbs->listRows();
		$rowsall = $dbs->total();
		$page = $dbs->currentPage();

		return $this->fetch('index',[
			'wk' => '',
			'dbs' => $dbs,
			'page' => $page,
			'listRows' => $listRows,
			'rowsall' => $rowsall,
		]);
	}

	//搜索
	public function search()
	{
		$keyword = trim(input('get.wk'));
		$attr = new Attr();
		if ($keyword == '') {
			$dbs = $attr->getAttrList();
		} else {
			$dbs = $attr->getAttrByWhere($keyword);
		}
		$listRows = $dbs->listRows();
		$rowsall = $dbs->total();
		$page = $dbs->currentPage();

		return $this->fetch('index',[
			'wk' => $keyword,
			'dbs' => $dbs,
			'page' => $page,
			'listRows' => $listRows,
			'rowsall' => $rowsall,
		]);
	}

	//查看产品属性信息
	public function readAttr()
	{
		$id = input('get.id/d');
		$orm = new Shoppdtype();
		$info = Attr::where('id',$id)->find();
		$arr = $orm->order(['sort'=>'asc','id'=>'desc'])->select()->toArray();
		$pdType = $orm->tree($arr);//对查询出来的产品类型进行排序
		$this->assign('info',$info);
		$this->assign('pdtype',$pdType);
		$this->view->engine->layout(false);
		return $this->fetch('readattr');
	}

	//添加属性
	public function add()
    {
		$info = input('post.info/a');
		$info['addtime'] = time(); 
		$attr = new Attr();
		$attr->allowField(true)->save($info);
		$this->rec['msg'] = "添加成功";
        return $this->rec;
    }

    //更新属性
	public function update()
    {
		$id=input('get.id/d');
		$info=input('post.info/a');
		if ($info['attr_input_type'] != 1) {
			$info['attr_value'] = "";
		}
		Attr::where('id',$id)->update($info);
		$this->rec['msg'] = "修改成功，请及时修改相应产品信息!";
        return $this->rec;
    }

    //单个删除
	public function delete()
    {
		$id = input('get.id/d');
		Attr::where('id',$id)->delete();
        return $this->rec;
    }

    //批量删除
	public function deletes()
    {
		$ids = input('post.IDS/a');
		if (is_array($ids)) Attr::where('id','in',$ids)->delete();
		$this->rec['msg'] = "删除成功，请及时修改相应产品信息!";
        return $this->rec;
    }

    //根据产品类型获取属性
    public function getAttr()
    {
    	$type_id = input('get.type_id/d');
    	$orm = new Shoppdtype();
    	$arr = Shoppdtype::where('flag',1)->select()->toArray();
    	$type_fid = $orm->getFatherIds(tree($arr),$type_id);
    	return Attr::where('type_id','in',$type_fid)->order(['attr_type'=>'asc','id'=>'asc'])->select()->toArray();
    }

    //根据属性id获取属性信息
    public function getAttrById()
    {
    	$id = input('get.id/d');
    	return Attr::get($id);
    }

}