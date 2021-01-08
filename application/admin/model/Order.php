<?php
namespace app\admin\model;

class Order extends BaseModel
{
    //订单类型
    const STATE = [
        0 => '',
        1 => '待付款',
        2 => '已付款,待发货',
        3 => '待收货,已发货',
        4 => '已收货,完成',
        5 => '关闭',
    ];

    protected $append = [
        'state_str',
    ];

    public function getStateStrAttr($value, $data)
    {
        if(isset($data['state'])){
            return static::STATE[$data['state']];
        }else{
            return '';
        }
    }
    
    public function orderGoods()
    {
        return $this->hasMany('OrderGoods', 'order_id', 'id');
    }

    public function orderAddress()
    {
        return $this->hasOne('OrderAddress', 'order_id', 'id');
    }
}