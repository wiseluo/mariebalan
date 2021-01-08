<?php
namespace app\index\controller;

use app\common\controller\Base;

class Help extends Base
{
	//联系我们
    public function arrivePay()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/arrivePay");
    }

    //联系我们
    public function checkGood()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/checkGood");
    }

    //投诉
    public function complain()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/complain");
    }

    //投诉列表
    public function complain_list()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/complain_list");
    }

    //联系我们
    public function link()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/link");
    }

    //5555555555555555555555555555555555555555555555555555555555555555555555555555555555555555
    
    //联系我们
    public function onlinePay()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/onlinePay");
    }

    //联系我们
    public function process()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/process");
    }

    //联系我们
    public function returnAddr()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/returnAddr");
    }

    //联系我们
    public function returnDesc()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/returnDesc");
    }

    //联系我们
    public function returnPolicy()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/returnPolicy");
    }

    //5555555555555555555555555555555555555555555555555555555555555555555555555555555555555555
    
    //联系我们
    public function selfShip()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/selfShip");
    }

    //联系我们
    public function shipArea()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/shipArea");
    }

    //联系我们
    public function usualQuest()
    {
        //$this->view->engine->layout(false);
        return $this->fetch("help/usualQuest");
    }
    //退款说明
    public function refund()
    {
    //$this->view->engine->layout(false);
    return $this->fetch("help/refund");
    }
    //商品税率表
    public function pricelist()
    {
    //$this->view->engine->layout(false);
    return $this->fetch("help/pricelist");
    }

    //商品税率表
    public function tariff()
    {
    //$this->view->engine->layout(false);
    return $this->fetch("help/tariff");
    }
    //邮费提示
    public function shipEse()
    {
    //$this->view->engine->layout(false);
    return $this->fetch("help/shipEse");
    }
    //常见问题
    public function faq()
    {
    //$this->view->engine->layout(false);
    return $this->fetch("help/faq");
    }
    //运费标准
    public function freight()
    {
    //$this->view->engine->layout(false);
    return $this->fetch("help/freight");
    }

    //物流跟踪
    public function logistracking()
    {
    //$this->view->engine->layout(false);
    return $this->fetch("help/logistracking");
    }
    //ceo邮箱
    public function mailbox()
    {
    //$this->view->engine->layout(false);
    return $this->fetch("help/mailbox");
    }

    //ceo邮箱
    public function returnFor()
    {
    //$this->view->engine->layout(false);
    return $this->fetch("help/returnFor");
    }
}