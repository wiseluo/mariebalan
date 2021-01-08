<?php

namespace app\api\controller;

use think\Request;
use think\Controller;
use app\api\service\CommentService;

class CommentGoodsController extends Controller
{
    public function __construct(CommentService $commentService)
    {
        parent::__construct();
        $this->commentService = $commentService;
    }

    public function commentGoods(Request $request)
    {
        $data = $request->param();
        $validate = validate('CommentValidate');
        if(!$validate->scene('commentGoods')->check($data)) {
            return json(['code'=> 401, 'type'=> 'commentGoods', 'msg'=> $validate->getError()]);
        }
        $param['page_size'] = $request->param('page_size',10);
        $param['goods_id'] = $request->param('goods_id', '');

        $list = $this->commentService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

}
