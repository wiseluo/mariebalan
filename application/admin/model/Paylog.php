<?php 
namespace app\admin\model;
use think\Model;

class Paylog extends Model 
{
	protected function getAddtimeAttr($addtime)
	{
		return date('Y-m-d', $addtime);
	}
}