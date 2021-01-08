<?php

namespace app\user\controller;

use think\Request;
use think\Controller;

class RefereeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function qrcode()
    {
        header("Content-type: image/png");
        $user = $this->_U;
        $url = common_func_domain(). '/user/register?referee='. $user['id'];
        include_once EXTEND_PATH.'/phpqrcode/phpqrcode.php';
        \Qrcode::png($url, $file = false, $level = 'L', $size = 7);
        exit();
    }
    
}
