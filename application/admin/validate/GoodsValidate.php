<?php

namespace app\admin\Validate;

use think\Validate;

class GoodsValidate extends Validate
{
    protected $rule = [
        'category_id' => 'require|integer',
        'material_id' => 'require|integer',
        'forum_id' => 'require|integer',
        'region' => 'require',
        'is_separate_buy' => 'require|in:0,1',
        'title' => 'require|max:60',
        'pics' => 'require',
        'price' => 'require|checkMoney',
        'original_price' => 'require|checkMoney',
        'attrs' => 'require|checkAttrs',
        'attrs_price' => 'require|checkAttrsPrice',
        'state' => 'require|in:0,1',
    ];
    
    protected $message = [
        'category_id.require' => '品类必填',
        'category_id.integer' => '品类id必须是数字',
        'material_id.require' => '材质必填',
        'material_id.integer' => '材质id必须是数字',
        'forum_id.require' => '版块必填',
        'forum_id.integer' => '版块id必须是数字',
        'region.require' => '购物区间必填',
        'is_separate_buy.require' => '支持单独购买必填',
        'is_separate_buy.in' => '支持单独购买值错误',
        'title.require' => '名称必填',
        'pics.require' => '图片必填',
        'price.require' => '促销价必填',
        'original_price.require' => '商品原价必填',
        'attrs.require' => '规格必填',
        'attrs_price.require' => '规格价格必填',
        'state.require' => '状态必填',
    ];
    
    protected $scene = [
        'save' => ['title', 'category_id', 'material_id', 'forum_id', 'is_separate_buy', 'title', 'pics', 'price', 'original_price', 'attrs', 'attrs_price'],
        'update' => ['title', 'category_id', 'material_id', 'forum_id', 'is_separate_buy', 'title', 'pics', 'price', 'original_price', 'attrs', 'attrs_price'],
        'shelf' => ['state'],
    ];
    
    public function checkMoney($value, $rule, $data)
    {
        if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $value)) {
            return '请输入正确的金额';
        } else {
            return true;
        }
    }

    public function checkAttrs($value, $rule, $data)
    {
        $attrs = json_decode($value, true);
        if(count($attrs) == 0) {
            return '请填写规格信息';
        }
        foreach($attrs as $k => $v) {
            if(!isset($v['akey']) || $v['akey'] == '') {
                return '商品规格键名必填';
            }
            if(!isset($v['avalue']) || $v['avalue'] == '') {
                return '商品规格键值必填';
            }
        }
        $avalue_arr = array_column($attrs, 'avalue');
        if(count($avalue_arr) != count(array_unique($avalue_arr))) {
            return '键值不能重复';
        }
        return true;
    }

    public function checkAttrsPrice($value, $rule, $data)
    {
        $attrs_price = json_decode($value, true);
        if(count($attrs_price) == 0) {
            return '请填写规格信息';
        }
        foreach($attrs_price as $k => $item) {
            if(!isset($item['sku']) || $item['sku'] == '') {
                return '规格sku必填';
            }
            if(!isset($item['price']) || !preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $item['price'])) {
                return '规格价格必填';
            }
            if(!isset($item['stock']) || !ctype_digit($item['stock'])) {
                return '规格库存必填且必须是数字';
            }
        }
        $sku_arr = array_column($attrs_price, 'sku');
        if(count($sku_arr) != count(array_unique($sku_arr))) {
            return 'sku不能重复';
        }
        return true;
    }
}

