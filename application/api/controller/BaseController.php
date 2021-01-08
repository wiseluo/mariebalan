<?php

namespace app\api\controller;

use think\Request;
use app\common\controller\CommonController;

class BaseController extends CommonController
{
    public function __construct()
    {
        parent::__construct();
    }
}
