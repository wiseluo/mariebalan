<?php
namespace app\api\model;
use think\Model;
use think\Db;

class Func extends Model
{

	public static function md5_pic($photos)
	{
		if(!empty($photos)){
            $photos = explode(',', $photos);
            foreach ($photos as $key => $value) {
                if(!empty($value)){
                    $photos[$key] = 'http://img.sumsoar.com/'.$value;
                }
            }
            $return = implode('|', $photos);
        }
    	return $return;
	}

	public static function domain_pic($data,$path="/uploads/products/")
	{
		if (is_array($data)){
			foreach ($data as $key => $value) {
				if($value) {
					if(substr($value,0,4) != 'http'){
						$data[$key] = request()->domain().$path.$value;
					}
				}else{
					if(count($data)== 1){
						$data = [];
					}else{
						$data[$key] = '';
					}
				}
			}
		}else{
			if(substr($data,0,4) != 'http'){
				$data = request()->domain().$path.$data;
			}
		}
    	return $data;
	}

	public static function goods_info($data,$arr = false)
	{
		if($arr){
			foreach ($data as $k => $v) {
				if(isset($v['addtime'])){
					$data[$k]['adddate'] = date('Y-m-d',$v['addtime']);
				}
				$data[$k]['pics'] = self::domain_pic(explode('|', $v['pics']));
    		}
		}else{
			$data['pics'] = self::domain_pic(explode('|', $data['pics']));
		}
    	return $data;
	}

	public static function logistics($address,$money,$weight,$dispatch = 0)
	{
		if($dispatch > 0){
			$dispatchs = Db::table('yw_dispatchs')->where('id',$dispatch)->find();
		}else{
			$dispatchs = Db::table('yw_dispatchs')->where('isdefault',1)->find();
		}
		$dispatchs['areas'] = unserialize($dispatchs['areas']);
		foreach ($dispatchs['areas'] as $key => $value) {//指定区域
			if(strpos($value['codes'],$address['town']) !== false){
				if($money > $value['freeprice']){//包邮
					return number_format('0.00',2);
				}
				if($weight > $value['firstnum']){//续重
					return bcadd(bcmul(ceil(($weight - $value['firstnum'])/$value['secondnum']),$value['secondprice'],2),$value['firstprice'],2);
				}else{//首重
					return number_format($value['firstprice'],2);
				}
			}
		}
		if($money > $dispatchs['freeprice']){//包邮
			return number_format('0.00',2);
		}
		if($weight > $dispatchs['firstnum']){//续重
			return bcadd(bcmul(ceil(($weight - $dispatchs['firstnum'])/$dispatchs['secondnum']),$dispatchs['secondprice'],2),$dispatchs['firstprice'],2);
		}else{//首重
			return number_format($dispatchs['firstprice'],2);
		}
	}

	public static function aids2attr($aids){
	    $attr = Db::table('yw_attrs')->whereIn('id',$aids)->column('avalue');
	    return $attr;
	}

	public static function addid2txt($address){
		unset($address['id']);
        $address['province'] = Db::table('yw_citys')->where('id',$address['province'])->value('name');
        $address['city'] = Db::table('yw_citys')->where('id',$address['city'])->value('name');
        $address['town'] = Db::table('yw_citys')->where('id',$address['town'])->value('name');
	    return $address;
	}

	public static function arr2tree($list, $id = 'id', $fid = 'fid', $sub = 'sub')
    {
        list($tree, $map) = [[], []];
        foreach ($list as $item) $map[$item[$id]] = $item;
        foreach ($list as $item) if (isset($item[$fid]) && isset($map[$item[$fid]])) {
            $map[$item[$fid]][$sub][] = &$map[$item[$id]];
        } else $tree[] = &$map[$item[$id]];
        unset($map);
        return $tree;
    }
}