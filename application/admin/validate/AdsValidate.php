<?php

namespace app\admin\Validate;

use think\Validate;

class AdsValidate extends Validate
{
    protected $rule = [
        'title' => 'require|max:60',
        'state' => 'require|in:0,1',
        'pic' => 'require',
        'sort' => 'require|integer',
    ];
    
    protected $message = [
        'title.require' => '标题必填',
        'state.require' => '是否显示必填',
        'state.in' => '是否显示错误',
        'pic.require' => '图片必填',
        'sort.require' => '排序必填',
        'sort.integer' => '排序必须是数字',
    ];
    
    protected $scene = [
        'save' => ['title', 'state', 'pic', 'sort'],
        'update' => ['title', 'state', 'pic', 'sort'],
    ];
    
}

