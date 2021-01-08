<?php

namespace app\api\controller;

use think\Request;
use think\Controller;
use app\api\service\ForumService;

class ForumController extends Controller
{
    public function __construct(ForumService $forumService)
    {
        parent::__construct();
        $this->forumService = $forumService;
    }

    public function index()
    {
        $list = $this->forumService->indexService();
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

}
