<?php

namespace app\admin\Validate;

use think\Validate;

class MaterialValidate extends Validate
{
    protected $rule = [
        'name' => 'require|max:30',
        'mod' => 'require',
        'cssname' => 'require',
        'cssico' => 'require',
        'sort' => 'require|integer',
    ];
    
    protected $message = [
        'name.require' => '名称必填',
        'state.require' => '是否显示必填',
        'state.in' => '是否显示错误',
        'mod.require' => '地址必填',
        'cssname.require' => '样式必填',
        'cssico.require' => '图标必填',
        'sort.require' => '排序必填',
        'sort.integer' => '排序必须是数字',
    ];
    
    protected $scene = [
        'save' => ['name', 'mod', 'cssname', 'cssico', 'sort'],
        'update' => ['name', 'mod', 'cssname', 'cssico', 'sort'],
    ];
    
}

