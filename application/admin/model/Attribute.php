<?php
namespace app\admin\model;
use think\Model;

class Attribute extends Model
{	
	protected $type = [
	 	'addtime' => 'timestamp:Y-m-d'
	];

	//关联产品属性值表（yw_pdattr）
	public function pdattr()
	{
		return $this->hasMany('Pdattr','attr_id','id');
	}
	//获取属性列表
	public function getAttrList()
	{
		$result = $this->view('Attribute','*')
		        	 ->view('Shoppdtype',['fid'=>'type_fid','name'=>'type_name'],'Shoppdtype.id=Attribute.type_id','LEFT')
		        	 ->order(['sort'=>'asc','id'=>'desc'])
		        	 ->paginate(10);
        return $result;
	}
	//根据条件查询
	public function getAttrByWhere($wk='')
	{
		$result = $this->view('Attribute','*')
		        	->view('Shoppdtype',['fid'=>'type_fid','name'=>'type_name'],'Shoppdtype.id=Attribute.type_id','LEFT')
		        	->whereOr('Attribute.attr_name','like',"%$wk%")
		        	->whereOr('Shoppdtype.name','like',"%$wk%")
		        	->order(['sort'=>'asc','id'=>'desc'])
		        	->paginate(10,false,[
						'query' => ['wk'=>"$wk"]
					]);

        return $result;
	}
}
