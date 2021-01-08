<?php

namespace app\index\controller;

use app\admin\model\Pdattr;
use app\admin\model\Shop;
use app\admin\model\Shoppd;
use app\common\controller\Base;
use think\facade\Cookie;
use think\Request;
use think\facade\Cache;

class Shopcar extends Base
{
    public function index()
    {
        $this->view->engine->layout(false);
        return view('shopcar');
    }
    /**
     * 购物车
     * @return \think\response\View
     */
    public function index1()
    {
        $this->view->engine->layout(false);
        $product = cookie('product');
        if(!empty($product)){
            foreach($product as $k=>$data){
                $shoppd = Shoppd::get($data['id']);
        		if ($shoppd->sid){
        		    $product[$k]['shop']['shopid'] = $shoppd->shop->id;
        	        $product[$k]['shop']['title'] = $shoppd->shop->title;
        		}else{
        			$product[$k]['shop']['shopid'] = $shoppd->id;
                    $product[$k]['shop']['title'] = $shoppd->title;
        		}
                
                $product[$k]['pic'] = $shoppd->onepic;
                $product[$k]['name'] = $shoppd->title;
            }
            $num = count(cookie('product'));
        }else{
            $product = [];
            $num = 0;
        }
//        Cookie::delete('product');
        return view('shopcar', ['product' => $product, 'num' => $num]);
    }

    /**
     * 添加产品到购物车
     */
    public function add(Request $request)
    {
        $num = $request->param('num');
        $id = json_decode($request->param('id'), 'array');
        $pid = $request->param('pid');
        $attr_name = [];
        $attr_price = [];
        $this_product = [];
        $pro = Shoppd::get($pid);
        $this_product['id'] = $pro->id;
//        $this_product['name'] = $pro->title;
//        $this_product['pic'] = $pro->onepic;
        if(!empty($id)){
            foreach ($id as $value) {
                $attr = Pdattr::get($value);
                $attr_name[] = $attr->attr_value;
                $attr_price[] = $attr->attr_price;
            }
            $attr_allprice = array_reduce($attr_price, function ($va1, $va2) {
                return $va1 + $va2;
            }, 0);
            $this_product['price'] = ($attr_allprice + $pro->price) * $num;
        }else{
            $this_product['price'] = $pro->price * $num;
        }
        $this_product['attr'] = $attr_name;
        $this_product['num'] = $num;

//        $this_product['shop'] = Shop::field('id,title')->where('id', $pro->sid)->find()->toArray();
        $old_product = cookie('product');
        $old_product[] = $this_product;
        cookie('product', $old_product);
        echo 1;
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $did = json_decode($id, 'array');
        if(!empty($did)){
            $cookie = cookie('product');
            foreach($did as $k=>$v){
                unset($cookie[$k]);
            }
            cookie('product',$cookie,3600);
            echo 1;
        }else{
            echo 0;
        }

    }


}
