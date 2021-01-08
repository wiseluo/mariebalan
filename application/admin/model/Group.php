<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30
 * Time: 10:03
 */
namespace app\admin\model;
use app\admin\model\Module;
use think\Model;
use think\Db;
class Group extends Model{
	//protected $append = ['access_qx'];

	protected function getAccessQxAttr($value,$data)
	{

		return Db::table('yw_module')->where("FIND_IN_SET(id, '{$data['access']}')")->value('GROUP_CONCAT(name separator " ")');
	
	}
}