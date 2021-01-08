<?php 
namespace app\admin\model;
use think\Model;

class Shmessage extends Model 
{
	public function getShuohuoAttr()
	{
		return $this->getData('consignee');
	}
}