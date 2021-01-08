<?php

namespace app\api\Validate;

use think\Validate;

class CommentValidate extends Validate
{
    protected $rule = [
        'goods_id' => 'require|integer',
        'order_goods_id' => 'require|integer',
        'star' => 'require|in:0,1,2,3,4,5',
        'anonymous' => 'require|in:0,1',
        'comment_id' => 'require|integer',
        'content' => 'require|integer',
    ];
    
    protected $message = [
        'goods_id.require' => '商品规格id必填',
        'goods_id.integer' => '商品规格id必须是数字',
        'order_goods_id.require' => '订单产品id必填',
        'order_goods_id.integer' => '订单产品id必须是数字',
        'star.require' => '星级必填',
        'star.in' => '星级错误',
        'anonymous.require' => '匿名必填',
        'anonymous.in' => '匿名错误',
        'comment_id.require' => '评价id必填',
        'comment_id.integer' => '评价id必须是数字',
        'content.require' => '内容必填',
    ];
    
    protected $scene = [
        'commentGoods' => ['goods_id'],
        'save' => ['order_goods_id', 'anonymous'],
        'commentReply' => ['comment_id', 'content'],
    ];
    
}

