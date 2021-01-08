<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\admin\model\News;

class Link extends Base
{
	//联系我们
    public function contact_us()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("link/contact_us");
    }

    //招聘
    public function zhao_us()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("link/zhao_us");
    }

    //商城服务协议
    public function service_us()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("link/service_us");
    }

    //
    public function dz_us()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("link/dz_us");
    }
}