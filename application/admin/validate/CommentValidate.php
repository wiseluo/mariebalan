<?php

namespace app\admin\Validate;

use think\Validate;

class CommentValidate extends Validate
{
    protected $rule = [
        'state' => 'require|in:0,1',
    ];
    
    protected $message = [
        'state.require' => '状态必填',
        'state.in' => '状态错误',
    ];
    
    protected $scene = [
        'update' => ['state'],
    ];
    
}

