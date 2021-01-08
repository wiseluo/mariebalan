<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\admin\model\Shoppd;
use app\admin\model\Shoppdtype;
use app\admin\model\Pdattr;
use app\admin\model\Brand;
use app\admin\model\Attribute as Attr;
use app\api\model\Func;
use think\Db;

class Pdlist extends Base
{
	public function index()
	{
	    $wk = input('wk');
        $map=array();
        if (!empty($wk)){
            $map['title']=['like','%'.$wk.'%'];
        }
		$sort = request()->param('sort');
		$catid = input('get.catid/d');

		//相关分类
		$hottype = Db::table('yw_types')->where('state',1)->where('fid',$catid)->select();
		$goods = Db::table('yw_goods')->where(
			function ($query) use($hottype,$catid) {
                if($hottype){
                	$tids = Db::table('yw_types')->where('fid',$catid)->column('id');
                    $query->whereIn('tid',implode(',',$tids));
                }else{
                	$query->where('tid',$catid);
                }
            }
		)->where('state',1)->whereOr($map)->order(['id'=>'desc'])->paginate(8);
		//一周热销
		$weekpds = Db::table('yw_goods')->limit(12)->order('hits','desc')->select();
        $pagestr = $goods->appends(['catid'=>$catid])->render();
		$this->assign('catid',$catid);
		$this->assign('sort',$sort);
		$this->assign('goods',Func::goods_info($goods->items(),true));
		$this->assign('hottype',$hottype);
		$this->assign('weekpds',$weekpds);
		$this->assign('pagestr',$pagestr);
		$this->assign('page',$goods->currentPage());
		return $this->fetch('pd_list');
	}

	public function index1()
	{
		$ptm = new Shoppdtype();
		$sort_arr = ['star'=>['star'=>'desc'],'sells'=>['sells'=>'desc'],'price'=>['price'=>'asc'],'asss'=>['asss'=>'desc'],'id'=>['id'=>'desc']];
		//获取产品分类id
		$id = input('get.catid/d');
		//获取排序字段  [star=>综合,sells=>销量,price=>价格,asss=>评论,id=>新品]
		$sort = input('get.sort/s');
		//获取搜索字段
		$wk = input('wk/s');
		$wk1 = input('post.wk/s');
		if(empty($sort)) $sort = 'star';
		if (!isset($id)) {
			//return $this->redirect('/index');
			$id = -1; 
		}
		//获取筛选条件信息
		$jnv = input('get.JN_V');
		$keep_f = input('get.keep_f');
		$pdid_arr = null;
		if (isset($jnv)) {
			$pdid_arr = $this->filtrate($jnv);
		} elseif (!isset($keep_f) || isset($wk1)) {
			session('filter',null);
		} elseif (isset($keep_f)) {
			$pdid_arr = $this->filtrate_del();
		}
		
		//获取页码
		$page = (input('get.page/d'))?:1;
		//每页显示多少个商品
		$pageSize = 40;
		$offset = ($page - 1)*$pageSize;
		//获取该产品分类和所有上级分类信息 
		$category = $ptm->getFathers($this->pd_type_arr, $id);
		//获取产品分类下的所有子分类id  包括自己
		$catIds_arr = getAllSubIds($this->pd_type_arr, $id, 'fid', 1);
		$subclass = null;
		if (count($catIds_arr) > 1) {
			//该分类不是最后一级分类  获取该分类下所有的子分类信息
			$subclass = getOneSub($this->pd_type_arr, $id);
		} elseif ($id == -1) {
			$subclass = Shoppdtype::where(['fid'=>0,'flag'=>1])->select();
		}
		//获取该分类下可作为筛选条件的所有属性id
		$classIds = Attr::where(['type_id'=>$id,'is_filtrate'=>1])->column('id');
		$search_result = null;
		if ($wk && !$category) $search_result = true;
		//获取对应的属性值
		$attr_arr = Pdattr::with(['attribute'=>function($query){$query->field('id,attr_name,sort')->bind('attr_name,sort');}])->where('attr_id','in',$classIds)->select();
		//dump($attr_arr);exit;
		$attr_arr = category_arr(bubble_sort($attr_arr, 'sort', 'asc', 'attr_id', 'asc'), 'attr_id', 'attr_value');
		//获取该产品分类下的所有产品
		if ($id == -1) {
			$where = ['tid'=>['in',$this->pd_typeId_arr],'flag'=>1];
		} else {
			$where = ['tid'=>['in',$catIds_arr],'flag'=>1];
		}
		if (isset($wk)) {
			$where['title'] = ['like',"%$wk%"];
		}
		if (isset($pdid_arr) && !empty($pdid_arr)) {
			$where['id'] = ['in',$pdid_arr];
		}
		$filter = null;
		if (session('filter')) {
			$filter = $this->filter_session(session('filter'));
			if (empty($pdid_arr)) {
				$where = ['id'=>-1];//没有筛选到商品
			}
		}   		
		$total = Shoppd::where($where)->count();//商品总数
		$products = Shoppd::where($where)->order($sort_arr[$sort])->limit($offset,$pageSize)->select();
		if ($products) {
			disposeMoreFile($products,'pic');
		}	
		$page_obj = new Page($total,$pageSize);
		$pagestr = $page_obj->show();
		//品牌
		$brands = Brand::where('flag',1)->select();
		//热门分类
		$hottype = Shoppdtype::where('flag',1)->where('fid','<>',0)->limit(8)->select();
		//热门关键词
		$hotwk = array('菜花油','红干','法国白兰地','土耳其蜂蜜','香槟','总动员套装');
		//一周热销
		$weekpds = Shoppd::field('id,title,price,pic')->where('readtime','>',(time()-7*86400))->limit(12)->order('hits','desc')->select();
		
		
		//dump($attr_arr);exit;
		$this->assign('brands',$brands);
		$this->assign('hottype',$hottype);
		$this->assign('hotwk',$hotwk);
		$this->assign('weekpds',$weekpds);
		$this->assign('catid',$id);
		$this->assign('sort',$sort);
		$this->assign('search_result',$search_result);
		
		
		$this->assign('filter',$filter);
		$this->assign('category',$category);
		$this->assign('subclass',$subclass);
		$this->assign('attr_arr',$attr_arr);
		$this->assign('products',$products);
		$this->assign('pagestr',$pagestr);
		$this->assign('total',$total);
		$this->assign('page',$page);
		$this->assign('pageNum',($total==0)?1:ceil($total/$pageSize));

		return $this->fetch('pd_list');
	}

	//搜素匹配
	public function search_match($wk)
	{
		dump(url('index/hello','id=5&name=thinkphp&sex=man'));exit;
		if (!request()->isAjax()) {
			return '无效请求';
		}
		if ($wk == '') {
			$result = 0;
		} else {
			$result = Shoppd::where(['tid'=>['in',$this->pd_typeId_arr],'flag'=>1,'title'=>['like',"%$wk%"]])->field('id,title')->select();
		}
		return json_encode($result);
	}

	//删除刷选
	public function delfilter()
	{
		$key = input('get.key/d');
		$filter = session('filter');
		$arr = explode('|',$filter);
		if ($arr) {
			array_splice($arr,$key,1);
			if ($arr) {
				$filter = implode('|',$arr);
				session('filter',$filter);
			} else {
				session('filter',null);
			}
		}
		return 1;
		
	}

	//刷选
	protected function filtrate($str)
	{
		if (count(explode('_',$str)) < 4) {
			return [];
		}
		$filter = session('filter');
		if (isset($filter)) {
			if (!in_array($str, explode('|',$filter))) {
				$filter = $filter.'|'.$str;
			}
			session('filter',$filter);
			$arr = explode('|',$filter);
		} else {
			$filter = $str;
			session('filter',$filter);
			$arr = explode('|',$filter);
		}
		return $this->getPdIdArr(dispose_filter($arr));	
	}

	protected function getPdIdArr($arr)
	{	
		$pdid_arr = [];
		foreach ($arr as $k => $v) {
			switch ($v['type']) {
				case 'a':
					array_splice($v,0,1);
					//dump($v);exit;
					$temp = Pdattr::where($v)->column('pd_id');
					break;
				case 'p':
					$where = $this->dispose_price($v['attr_value'][1]);
					//dump($where);exit;
					$temp = Shoppd::where($where)->column('id');
					break;
				case 'b':
					$where = ['bid'=>$v['attr_id']];
					//dump($where);exit;
					$temp = Shoppd::where($where)->column('id');
					break;
			}
			$pdid_arr = ($k==0)?$temp:$pdid_arr;
			$pdid_arr = array_intersect($pdid_arr,$temp);
		}
		//dump($pdid_arr);exit;
		return $pdid_arr;
	}

	protected function dispose_price($str)
	{
		$price = explode('-',$str);
		if (count($price) > 1) {
			$where = ['price'=>[['egt',floatval($price[0])], ['lt',floatval($price[1])]]];
		} else {
			$where = ['price'=>['egt',floatval($price[0])]];
		}
		return $where;
	}

	protected function filter_session($arr)
	{
		$res = [];
		$arr = explode('|',$arr);
		foreach ($arr as $k => $v) {
			$f_arr = explode('_',$v);
			$res[$k]['id'] = $f_arr[1];
			$res[$k]['name'] = $f_arr[2];
			$num = 1;
			for ($i=3; $i < count($f_arr); $i++) { 
				$res[$k]['value'][$num] = $f_arr[$i];
				$num++;
			}
			$res[$k]['value_len'] = $num-1;
		}
		return $res;
	}

	protected function filtrate_del()
	{
		$filter = session('filter');
		if (isset($filter)) {
			$arr = explode('|',$filter);
		} else {
			$arr = [];
		}
		return $this->getPdIdArr(dispose_filter($arr));	
	}
}