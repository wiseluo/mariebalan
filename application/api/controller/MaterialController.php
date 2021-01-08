<?php

namespace app\api\controller;

use think\Request;
use think\Controller;
use app\api\service\MaterialService;

class MaterialController extends Controller
{
    public function __construct(MaterialService $materialService)
    {
        parent::__construct();
        $this->materialService = $materialService;
    }

    public function index()
    {
        $list = $this->materialService->indexService();
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

}
