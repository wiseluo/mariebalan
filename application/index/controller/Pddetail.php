<?php
namespace app\index\controller;
use app\common\controller\Ucenter;
use app\common\controller\Base;
use app\admin\model\Shoppd;
use app\admin\model\Shoppdtype;
use app\admin\model\Pdattr;
use app\admin\model\Pdprice;
use app\admin\model\Attribute;
use app\admin\model\Orderproduct;
use app\ucenter\model\Mycomment;
use app\ucenter\model\Collect;
use think\facade\Session;
use think\facade\Cookie;
use think\Db;
use app\api\model\Func;
class Pddetail extends Base
{
	const SACLE = 5;   //评论每页数量

	public function index(){
		$id = input('get.id/d');
		$goods = Db::table('yw_goods')->where(['id'=>$id])->find();
		$Category = Db::table('yw_types')->where('id',$goods['tid'])->find();
        $goods['attr'] = Db::table('yw_attrs')->where('gid',$id)->field('id,akey,avalue')->select();
        if($goods['attr']){
            $attrs = array();
            foreach ($goods['attr'] as $key => $value) {
                $attrs[$value['akey']][] = $value;
            }
            $goods['attr'] = array();
            foreach ($attrs as $key => $value) {
                array_push($goods['attr'],['spec_kind'=>$key,'spec'=>$value]);
            }
        }

        $goods['attr_price'] = Db::table('yw_attrs_price')->where('gid',$id)->field('id,aids,price,stock')->select();
        if($goods['attr_price']){
            $minmax = array();
            foreach($goods['attr_price'] as $key=>$value){
                array_push($minmax, $value['price']);
            }
            sort($minmax);
            $goods['minprice'] = $minmax[0];
            $goods['maxprice'] = $minmax[count($minmax)-1];
        }else{
            $goods['minprice'] = $goods['price'];
            $goods['maxprice'] = $goods['price'];
        }
        if(isset($this->user)){
        	$goods['favorite'] = Db::table('yw_favorite')->where(['uid'=>$this->user['id'],'gid'=>$id])->count();
        }else{
        	$goods['favorite'] = 0;
        }
        $prices = array();
        if (!empty($goods['attr_price']) ){
            foreach ($goods['attr_price'] as $k => $v){
                $prices[$k] = $v['price'];
            }
            if (count($prices)> 1 ){
                $maxnum = count($prices) - 1;
                $interval_value = $prices[0].'~'.$prices[$maxnum];
                $verdict_arr = 0;
            }else{
                $verdict_arr = 1;
                $interval_value = $goods['price'];
            }
        }

        //相关分类
		$hottype = Db::table('yw_types')->where('state',1)->where('fid',$goods['tid'])->limit(8)->select();
		//大家都在看
		$hotpd = Db::table('yw_goods')->where('state',1)->orderRaw('rand()')->limit(5)->select();
		//同类推荐
		$simipds = Db::table('yw_goods')->where('tid',$goods['tid'])->limit(12)->order('id','desc')->select();
		//一周热销
		$weekpds = Db::table('yw_goods')->limit(12)->order('hits','desc')->select();

		Db::table('yw_goods')->where('id',$id)->setInc('hits');
		$stock = 0 ;
		foreach ($goods['attr_price'] as $k => $v){
          	 $stock += $v['stock'];
       		 }
		$this->assign('weekpds',Func::goods_info($weekpds,true));
		$this->assign('verdict_arr',$verdict_arr);
		$this->assign('simipds',$simipds);
		$this->assign('hotpd',Func::goods_info($hotpd,true));
		$this->assign('hottype',$hottype);
		$this->assign('interval_value',$interval_value);
		$this->assign('goods',Func::goods_info($goods));
		$this->assign('Category',$Category);
		$this->assign('stock',$stock);
		return $this->fetch('pd_detail');
	}

	public function index1()
	{
		// $user = Cookie::get('cuser');
		// dump($user);
		$userid = $this->cookie_uid;
		//dump($userid['id']);exit;
		
		$ptm = new Shoppdtype();
		//获取产品id
		$id = input('get.id/d');
		if (!isset($id)) {
			return $this->redirect('/index');
		}
		//获取产品所有信息
		$product = Shoppd::with(['brand'=>function($query){$query->where('flag',1)->field('id,name');},'pdattr'=>['pdtype'=>function($query){$query->bind(['pdtype_fid'=>'fid','pdtype_name'=>'name']);},'attribute'=>function($query){$query->where('flag',1)->bind('attr_name,attr_type,sort');}]])->find($id);
		if (!$product) {
			return $this->redirect('/index');
		}
		Shoppd::update(array('hits'=>$product['hits']+1,'readtime'=>time()),array('id'=>$id));
		//获取产品的分类和所有上级分类信息
		$Category = $ptm->getFathers(Shoppdtype::where('flag',1)->select(),$product->tid);
		//将产品的图片路径字符串转换成图片数组
		$product_pics = null;
		if ($product['pic']) {
			$product_pics = getPicArr(disposeFile($product['pic']));

		}
		$product = $product->toarray();

		$pdprice = Pdprice::where('pd_id',$id)->column('price','pdattr_id');

		$this->assign('pdprice',json_encode($pdprice));
		if ($product['pdattr']) {
			$product['pdattr'] = category_arr(bubble_sort($product['pdattr'], 'attr_id', 'asc', 'id', 'asc'), 'attr_id', 'attr_value');
		}

		//热门分类
		$hottype=Shoppdtype::where('flag',1)->where('fid','<>',0)->limit(8)->select();
		//大家都在看
		$hotpd=Shoppd::field('id,title,pic,price')->where('tid',$product['tid'])->limit(3)->order('readtime','desc')->select();
		//同类推荐
		$simipds=Shoppd::field('id,title')->where('tid',$product['tid'])->where('level',2)->limit(12)->order('id','desc')->select();
		//一周热销
		$weekpds=Shoppd::field('id,title,price,pic')->where('readtime','>',(time()-7*86400))->limit(12)->order('hits','desc')->select();

		/********************************************************/
		//商品评论
		$shop_comment = Mycomment::field('id,order_id,product_id,user_id,username,photos,star,content,create_time')
									->where('product_id',$id)->order('create_time','desc')->paginate(self::SACLE);
		//所有评价
		$count_comment = count(Mycomment::field('id')->where('product_id',$id)->select());
	
		$page = $shop_comment->render(); 

		//评论平均值 
		$avg_comment = Mycomment::where('product_id',$id)->avg('star');
		$avg_comment = ceil($avg_comment*20);
		if(empty($count_comment)) {   //暂无评价，好评为100
			$avg_comment = 100;
		}

		$npage = input('get.page');
		//判断商品是否已收藏
		$is_shouchang = Collect::where('user_id',$userid)->where('shoppd_id',$id)->find();
		/*******************************************************/
		$is_shouchang = count($is_shouchang);

		if($npage){
			$shop_comment = Mycomment::field('id,order_id,product_id,user_id,username,photos,star,content,create_time')
									->where('product_id',$id)->order('create_time','desc')->limit(($npage-1)*self::SACLE,self::SACLE)->select();
			$this->assign('id',$id);
			$this->assign('avg_comment',$avg_comment);
			$this->assign('shop_comment',$shop_comment);
			$this->assign('is_shouchang',$is_shouchang);
			$this->assign('count_comment',$count_comment);
			$this->assign('page',$page);
			$this->assign('weekpds',$weekpds);
			$this->assign('simipds',$simipds);
			$this->assign('hotpd',$hotpd);
			$this->assign('hottype',$hottype);
			$this->assign('product',$product);
			$this->assign('Category',$Category);
			$this->assign('product_pics',$product_pics);
			$this->view->engine->layout(false);
			return 	$this->fetch('comment');
		}
	
		$this->assign('id',$id);
		$this->assign('avg_comment',$avg_comment);
		$this->assign('shop_comment',$shop_comment);
		$this->assign('is_shouchang',$is_shouchang);
		$this->assign('count_comment',$count_comment);
		$this->assign('page',$page);
		$this->assign('weekpds',$weekpds);
		$this->assign('simipds',$simipds);
		$this->assign('hotpd',$hotpd);
		$this->assign('hottype',$hottype);
		$this->assign('product',$product);
		$this->assign('Category',$Category);
		$this->assign('product_pics',$product_pics);
		return $this->fetch('pd_detail');
	}

}