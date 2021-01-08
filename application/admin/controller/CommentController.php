<?php

namespace app\admin\controller;

use think\Request;
use app\admin\service\CommentService;

class CommentController extends BaseController
{
    public function __construct(CommentService $commentService)
    {
        parent::__construct();
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $param['keyword'] = $request->param('keyword', '');

        $list = $this->commentService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
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

    // public function save(Request $request)
    // {
    //     $param = $request->param();
    //     $validate = validate('CommentValidate');
    //     if(!$validate->scene('save')->check($param)) {
    //         return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
    //     }
    //     $res = $this->commentService->saveService($param);
    //     if($res['status']) {
    //         return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
    //     }else{
    //         return json(['code'=> 401, 'msg'=> $res['msg']]);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $param = $request->param();
        $validate = validate('CommentValidate');
        if(!$validate->scene('update')->check($param)) {
            return json(['code'=> 401, 'type'=> 'update', 'msg'=> $validate->getError()]);
        }
        $res = $this->commentService->updateService($param, $id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->commentService->deleteService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
}
