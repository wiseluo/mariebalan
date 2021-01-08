<?php

namespace app\api\controller;

use think\Request;
use app\api\service\ShopcartService;

class ShopcartController extends BaseController
{
    public function __construct(ShopcartService $shopcartService)
    {
        parent::__construct();
        $this->shopcartService = $shopcartService;
    }

    public function index(Request $request)
    {
        // $param['page'] = $request->param('page',1);
        // $param['page_size'] = $request->param('page_size',10);
        $param['user_id'] = $request->user()['id'];

        $list = $this->shopcartService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('ShopcartValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $param['user_id'] = $request->user()['id'];
        $res = $this->shopcartService->saveService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function update(Request $request, $id)
    {
        $param = $request->param();
        $validate = validate('ShopcartValidate');
        if(!$validate->scene('update')->check($param)) {
            return json(['code'=> 401, 'type'=> 'update', 'msg'=> $validate->getError()]);
        }
        $param['user_id'] = $request->user()['id'];
        $res = $this->shopcartService->updateService($param, $id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->shopcartService->deleteService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
    public function batchDelete(Request $request)
    {
        $param = $request->param();
        $validate = validate('ShopcartValidate');
        if(!$validate->scene('batchDelete')->check($param)) {
            return json(['code'=> 401, 'type'=> 'batchDelete', 'msg'=> $validate->getError()]);
        }
        $param['user_id'] = $request->user()['id'];
        $res = $this->shopcartService->batchDeleteService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
}
