<?php
namespace app\api\model;

class Order extends BaseModel
{
    protected $table = 'yw_order';


    public function orderGoods()
    {
        return $this->hasMany('OrderGoods', 'order_id', 'id');
    }
}