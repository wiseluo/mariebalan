<?php

namespace app\api\controller;

use think\Request;
use think\Controller;
use app\api\service\CategoryService;

class CategoryController extends Controller
{
    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $list = $this->categoryService->indexService();
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

}
