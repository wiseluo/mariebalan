<?php

namespace app\admin\controller;

use think\Request;
use app\admin\service\UserService;

class UserController extends BaseController
{
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $param['page_size'] = $request->param('page_size',10);
        $param['keyword'] = $request->param('keyword', '');

        $list = $this->userService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function read($id)
    {
        $res = $this->userService->readService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    // public function save(Request $request)
    // {
    //     $param = $request->param();
    //     $validate = validate('UserValidate');
    //     if(!$validate->scene('save')->check($param)) {
    //         return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
    //     }
    //     $res = $this->userService->saveService($param);
    //     if($res['status']) {
    //         return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
    //     }else{
    //         return json(['code'=> 401, 'msg'=> $res['msg']]);
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     $param = $request->param();
    //     $validate = validate('UserValidate');
    //     if(!$validate->scene('update')->check($param)) {
    //         return json(['code'=> 401, 'type'=> 'update', 'msg'=> $validate->getError()]);
    //     }
    //     $res = $this->userService->updateService($param, $id);
    //     if($res['status']) {
    //         return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
    //     }else{
    //         return json(['code'=> 401, 'msg'=> $res['msg']]);
    //     }
    // }

    public function delete($id)
    {
        $res = $this->userService->deleteService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
}
