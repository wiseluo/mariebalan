<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use app\admin\model\Goods;
use think\Db;
use app\api\model\Func;
use think\Request;
use think\facade\Session;

class Shoppds extends Admin
{
    public function index()
	{
        $keyword = trim(input('get.wk'));
        if ($keyword == '') {
            $goods = Goods::order(['id'=>'desc'])->paginate(10);
        } else {
            $goods = Goods::table('yw_goods')->where('title','like',"%$keyword%")->order(['id'=>'desc'])->paginate(10,false,['query'=>['wk'=>$keyword]]);
        }
        $pager = $goods->render();
        $types = Db::table('yw_category')->column('name','id');
		$this->assign('types',$types);
        $this->assign('wk',$keyword);
		$this->assign('goods',Func::goods_info($goods->items(),true));
		$this->assign('pager',$pager);
		return $this->fetch();
	}
    public function xiaoliang(){
        $ids = Db::table('yw_goods')->select()->toArray();
        foreach ($ids as $k=>$v){
            $num = rand(10,1000);
            $res = Db::table('yw_goods')->where('id',$v['id'])->update(['sells'=>$num]);
        }
    }
	public function readpd()
	{
		$id = input('get.id/d');
        $types = Db::table('yw_category')->order(['sort'=>'asc'])->select()->toArray();//获取一级分类

        $info = Db::table('yw_goods')->where(['id'=>$id])->find();
		$attr = Db::table('yw_attrs')->where('goods_id',$id)->select()->toArray();
		$attrs = array();
        foreach ($attr as $key => $value) {
            $attrs[$value['akey']][] = $value;
        }
        $attr = array();
        foreach ($attrs as $key => $value) {
            array_push($attr,['spec_kind'=>$key,'spec'=>$value]);
        }
	
		$attr_price = Db::table('yw_attrs_price')->where('goods_id',$id)->select()->toArray();
		foreach ($attr_price as $key => $value) {
			$attr_price[$key]['attr'] = implode(' ',Func::aids2attr($value['aids']));
		}
		$this->assign('types',$types);
		$this->assign('uid',Session::get('admin')['id']);//当前登录用户id
		$this->assign('addusersname',Session::get('admin')['username']);//当前登录用户名称
		$this->assign('pics',$info['pics']);
		$this->assign('info',Func::goods_info($info));
		$this->assign('attr',$attr);
		$this->assign('attr_price',$attr_price);
		return $this->fetch();
	}

	public function savePd()
	{
//	    dump(input('post.'));die;
		$id = input('get.id/d');
		$info = input('post.info/a');
		if(!$info['pics'])unset($info['pics']);
		$attr = input('post.attr/a');
		$attr_price = input('post.attr_price/a');
		if($id > 0){
			//$info['content'] = str_replace('../../uploads/','http://shop.cbbestinfo.com/uploads/',$info['content']);
			Db::table('yw_goods')->where(['id'=>$id])->strict(false)->update($info);
		}else{
			$info['create_time'] = time();
			$id = Db::table('yw_goods')->strict(false)->insertGetId($info);
		}

		if($attr){
			$attrs = array();
			$ids = array();
			$attrsids = Db::table('yw_attrs')->where('goods_id',$id)->column('id');
			foreach ($attr as $key => $value) {
				if($value['id'] > 0){
					array_push($ids, $value['id']);
					Db::table('yw_attrs')->where('id',$value['id'])->update($value);
				}else{
					array_push($attrs, ['goods_id'=>$id,'akey'=>$value['akey'],'avalue'=>$value['avalue']]);
				}
			}
			Db::table('yw_attrs')->whereIn('id',implode(',', array_diff($attrsids,$ids)))->delete();
			Db::table('yw_attrs')->strict(false)->insertAll($attrs);
		}

		if($attr_price){
			$values = Db::table('yw_attrs')->where('goods_id',$id)->column('id','avalue');
			$prices = array();
			$ids = array();
			$priceids = Db::table('yw_attrs_price')->where('goods_id',$id)->column('id');
			foreach ($attr_price as $key => $value) {
				$valuearr = explode(',', $value['values']);
				foreach ($valuearr as $akey => $avalue) {
					$valuearr[$akey] = $values[$avalue];
				}
				$value['aids'] = implode(',', $valuearr);
				if($value['id'] > 0){
					array_push($ids, $value['id']);
					Db::table('yw_attrs_price')->where('id',$value['id'])->strict(false)->update($value);
				}else{
					array_push($prices, ['goods_id'=>$id,'aids'=>$value['aids'],'sku'=>$value['sku'],'price'=>$value['price'],'stock'=>$value['stock']]);
				}
			}
			Db::table('yw_attrs_price')->whereIn('id',implode(',', array_diff($priceids,$ids)))->delete();
			Db::table('yw_attrs_price')->strict(false)->insertAll($prices);
		}
		$this->rec['msg'] = "保存成功";
		return $this->rec;
	}

	//单个删除
	public function deletePd()
    {
		$id = input('get.id');
		$id = (int)$id;
		$info = Db::table('yw_goods')->where('id',$id)->delete();
		Db::table('yw_attrs')->where('goods_id',$id)->delete();
		Db::table('yw_attrs_price')->where('goods_id',$id)->delete();
		$this->rec['msg'] = "删除成功";
        return $this->rec;
    }
    //批量删除
	// public function deletes()
 //    {
	// 	$ids = input('post.IDS/a');
	// 	if(is_array($ids)){
	// 		$info = Db::table('yw_goods')->where('id','in',$ids)->delete();
	// 		Db::table('yw_attrs')->where('goods_id','in',$ids)->delete();
	// 		Db::table('yw_attrs_price')->where('goods_id','in',$ids)->delete();
	// 	}
	// 	$this->rec['msg'] = "删除成功";
 //        return $this->rec;
 //    }
    //批量审核
	public function makeFlag()
    {
		$ids=input('post.IDS/a');
		if(is_array($ids))$info = Db::table('yw_goods')->where('id','in',$ids)->update(['state'=>1]);
		$this->rec['msg'] = "审核成功";
        return $this->rec;
    }
	
	//取消审核
	public function canelFlag()
    {
		$ids=input('post.IDS/a');		
		if(is_array($ids))$info = Db::table('yw_goods')->where('id','in',$ids)->update(['state'=>0]);
		$this->rec['msg'] = "取消审核成功";
        return $this->rec;
    }

}