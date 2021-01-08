<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use think\Db;
use page\Page;

class Spread extends Admin{

    public function product_spread_statistics(){
        $wk        = request()->param('wk');
        $page      = request()->param('page',1);
        $page_size = request()->param('page_size',10);
        
        $order_by_field = request()->param('order_by_field','');
        $order_by_type  = request()->param('order_by_type',0,'intval');


        $query = Db::table('yw_order_goods')->alias('og')
                    ->field([
                        'og.goods_id',
                        'count(o.id) as order_nums',
                        'sum( if(o.state>1,1,0) ) as payment_nums',
                        'sum( if(og.comment_state>0,1,0) ) as refund_order_nums',
                        'count( distinct o.user_id) as spread_member_nums'
                    ])
                    ->join('yw_order o','o.id=og.order_id','left')
                    ->group('og.goods_id')
                    ->buildSql();

        $list_data =  Db::table('yw_goods')->alias('g')
                        ->field('g.title,g.hits,tgg.order_nums,tgg.payment_nums,tgg.refund_order_nums,tgg.spread_member_nums')
                        ->join($query.' as tgg','tgg.goods_id=g.id','left')
                        ->paginate($page_size);

        // 商品名称，推广数，成交量，退款数，未支付数，参与推广人数,最佳推广员
        foreach($list_data as $key => $value){
            $new_data[$key]['goods_id']                = $value['goods_id'];             // 商品id
            $new_data[$key]['title']              = $value['title'];             // 商品名称
            $new_data[$key]['spread_nums']        = (int)$value['hits'];  // 推广数 // todo: 
            $new_data[$key]['order_nums']         = (int)$value['order_nums'];   // 订单数
            $new_data[$key]['refund_order_nums']  = (int)$value['refund_order_nums'];   // 退款订单数
            $new_data[$key]['payment_nums']       = (int)$value['payment_nums']; // 成交量
            $new_data[$key]['spread_member_nums'] = (int)$value['spread_member_nums']; // 参与推广人数
            
            // 获取最多该商品的推广员名称 todo 没必要的话就删了ba
            // $tmp[$key]['sss'] = $this->_take_member_spread_statistics_data(1,1,['goods_id'=>$value['goods_id']],['payment_nums'=>'desc']);

            // $new_data[$key]['best_spread_member_name'] = (string)$tmp[$key]['sss'][0]['nickname']; // 最佳推广员 :Todo
            
        }

        $this->assign('list_data',$new_data);
        $this->assign('pages',$list_data->render());
        return $this->fetch("product_spread_statistics");


    }
    public function member_spread_statistics22(){
        $page      = request()->param('page',1);
        $page_size = request()->param('page_size',10);
        
        $order_by_field = request()->param('order_by_field','');
        $order_by_type  = request()->param('order_by_type',0,'intval');

        $query = Db::table('yw_order_goods')->alias('og')
                    ->field([
                        'o.user_id',
                        'og.goods_id',
                        'count(o.id) as order_nums',
                        'sum( if(o.state>1,1,0) ) as payment_nums',
                        'sum( if(og.comment_state>0,1,0) ) as refund_order_nums',
                        'count( distinct og.goods_id) as spread_goods_nums'
                    ])
                    ->join('yw_order o','o.id=og.order_id','left')
                    ->join('yw_goods g','g.id=og.goods_id','left')
                    ->group('o.user_id')
                    ->buildSql();

        $list_data =  Db::table('yw_user')->alias('u')
                        ->field('tgg.order_nums,tgg.payment_nums,tgg.refund_order_nums,tgg.spread_goods_nums,u.nickname')
                        ->join($query.' as tgg','tgg.user_id=u.id')
                        ->paginate($page_size);

        // 商品名称，推广数，成交量，退款数，未支付数，参与推广人数,最佳推广员
        foreach($list_data as $key => $value){
            $new_data[$key]['nickname']           = $value['nickname'];          // 商品名称
            $new_data[$key]['spread_nums']        = (int)$value['spread_nums'];  // 推广点击数 // todo: 
            $new_data[$key]['order_nums']         = (int)$value['order_nums'];   // 订单数
            $new_data[$key]['payment_nums']       = (int)$value['payment_nums']; // 成交量
            $new_data[$key]['refund_order_nums']  = (int)$value['refund_order_nums'];   // 退款订单数
            $new_data[$key]['spread_goods_nums'] = (int)$value['spread_goods_nums']; // 参与推广商品数
            $new_data[$key]['best_spread_goods_name'] = (string)$value['best_spread_goods_name']; // 最佳推广商品 :Todo
        }

        $this->assign('list_data',$new_data);
        $this->assign('pages',$list_data->render());
        return $this->fetch("member_spread_statistics");
    }
    public function member_spread_statistics(){
        $page      = request()->param('page',1);
        $page_size = request()->param('page_size',10);
        
        $order_by_field = request()->param('order_by_field','');
        $order_by_type  = request()->param('order_by_type',0,'intval');


        $data = $this->_take_member_spread_statistics_data($page,$page_size,$map,$order);


        // 商品名称，推广数，成交量，退款数，未支付数，参与推广人数,最佳推广员
        foreach($data as $key => $value){
            $new_data[$key]['nickname']           = $value['nickname'];          // 商品名称
            $new_data[$key]['spread_nums']        = (int)$value['spread_nums'];  // 推广点击数 // todo: 
            $new_data[$key]['order_nums']         = (int)$value['order_nums'];   // 订单数
            $new_data[$key]['payment_nums']       = (int)$value['payment_nums']; // 成交量
            $new_data[$key]['refund_order_nums']  = (int)$value['refund_order_nums'];   // 退款订单数
            $new_data[$key]['spread_goods_nums'] = (int)$value['spread_goods_nums']; // 参与推广商品数
            $new_data[$key]['best_spread_goods_name'] = (string)$value['best_spread_goods_name']; // 最佳推广商品 :Todo
        }

		$this->assign('list_data',$new_data);
		return $this->fetch("");
    }

    private function _take_member_spread_statistics_data($page,$page_size,$map,$order){
        $sql_where = "";
        $order_by  = "";
        $offset = $this->_getOffset($page,$page_size);

        if(isset($map['goods_id'])){
            $sql_where = " AND og.goods_id = ".$map['goods_id'];
        }
        
        if(isset($order['payment_nums'])){
            $order_by = 'ORDER BY payment_nums '.$order['payment_nums'];
        }

        $data = Db::query(" SELECT 
                            `u`.`nickname`,count(o.id) as order_nums,sum( if(og.comment_state>0,1,0) ) as refund_order_nums,sum( if(o.state>1,1,0) ) as payment_nums,count( distinct og.goods_id) as spread_goods_nums 
                            FROM `yw_order_goods` `og` 
                            LEFT JOIN `yw_order` `o` ON `og`.`order_id`=`o`.`id` 
                            LEFT JOIN `yw_user` `u` ON `o`.`user_id`=`u`.`id` 
                            WHERE `o`.`user_id` > 0 
                            ".$sql_where."
                            GROUP BY `o`.`user_id` 
                            ".$order_by."
                            LIMIT ".(int)$offset.",".(int)$page_size." ");


        return $data;
    }


    private function _take_product_spread_statistics_data($page,$page_size,$map,$order){
        $offset = $this->_getOffset($page,$page_size);
        $sql_where = "";


        $data = Db::query(" SELECT 
                            `og`.`title`,`og`.`goods_id`,count(o.id) as order_nums,sum( if(og.comment_state>0,1,0) ) as refund_order_nums,sum( if(o.state>1,1,0) ) as payment_nums,count( distinct o.user_id) as spread_member_nums ,`g`.hits
                            FROM `yw_goods` `g` 
                            LEFT JOIN `yw_order_goods` `og` ON `og`.`goods_id`=`g`.`id` 
                            LEFT JOIN `yw_order` `o` ON `og`.`order_id`=`o`.`id` 
                            WHERE `og`.`goods_id` > 0 
                            ".$sql_where."
                            GROUP BY `og`.`goods_id` 
                            LIMIT ".(int)$offset.",".(int)$page_size." ");

        $nums_data = Db::query(" SELECT 
                            count(`g`.`id`) as count_num
                            FROM `yw_goods` `g` 
                            ".$sql_where."
                             ");

        $total = $nums_data[0]['count_num'];

        return ['data'=>$data,'total'=>$total];
    }

    private function _getOffset($page=1,$page_size=10){
        $offset = ($page - 1) * $page_size;
        $offset = $offset < 0 ? 0 : $offset;
        return $offset;
    }

}