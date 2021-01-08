<?php

namespace app\api\controller;

use think\Request;
use think\Controller;
use app\api\service\AdsService;

class AdsController extends Controller
{
    public function __construct(AdsService $adsService)
    {
        parent::__construct();
        $this->adsService = $adsService;
    }

    public function adsSlide()
    {
        $list = $this->adsService->adsSlideService();
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

}
