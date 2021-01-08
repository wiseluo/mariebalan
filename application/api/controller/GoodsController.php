<?php

namespace app\api\controller;

use think\Request;
use think\Controller;
use app\api\service\GoodsService;

class GoodsController extends Controller
{
    public function __construct(GoodsService $goodsService)
    {
        parent::__construct();
        $this->goodsService = $goodsService;
    }

    public function index(Request $request)
    {
        $param['page_size'] = $request->param('page_size',10);
        $param['orderby'] = $request->param('orderby', 'id');
        $param['sort'] = $request->param('sort', 'desc');
        $param['category_id'] = $request->param('category_id', '');
        $param['material_id'] = $request->param('material_id', '');
        $param['forum_id'] = $request->param('forum_id', '');
        $param['region'] = $request->param('region', '');
        $param['keyword'] = $request->param('keyword', '');

        $list = $this->goodsService->indexService($param);
        return json(['code'=> 200, 'msg'=> '', 'data'=> $list]);
    }

    public function read($id)
    {
        $res = $this->goodsService->readService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

}
