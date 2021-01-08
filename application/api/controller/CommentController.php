<?php

namespace app\api\controller;

use think\Request;
use app\api\service\CommentService;

class CommentController extends BaseController
{
    public function __construct(CommentService $commentService)
    {
        parent::__construct();
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $list = $this->commentService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('CommentValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $param['star'] = $request->param('star', 5);
        $param['anonymous'] = $request->param('anonymous', 0);
        $param['content'] = $request->param('content', '');
        $param['photos'] = $request->param('photos', null);
        $param['user_id'] = $request->user()['id'];
        $res = $this->commentService->saveService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function read($id)
    {
        $res = $this->commentService->readService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function commentReply(Request $request)
    {
        $param = $request->param();
        $validate = validate('CommentValidate');
        if(!$validate->scene('commentReply')->check($param)) {
            return json(['code'=> 401, 'type'=> 'commentReply', 'msg'=> $validate->getError()]);
        }
        $param['user_id'] = $request->user()['id'];
        $res = $this->commentService->commentReplyService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function userCommented(Request $request)
    {
        $data = $request->param();
        $param['page_size'] = $request->param('page_size',10);
        $param['user_id'] = $request->user()['id'];

        $list = $this->commentService->userCommentedService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }
}
