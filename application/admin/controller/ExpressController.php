<?php

namespace app\admin\controller;

use think\Request;
use app\admin\service\ExpressService;

class ExpressController extends BaseController
{
    public function __construct(ExpressService $expressService)
    {
        parent::__construct();
        $this->expressService = $expressService;
    }

    public function index(Request $request)
    {
        $param['keyword'] = $request->param('keyword', '');

        $list = $this->expressService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function read($id)
    {
        $res = $this->expressService->readService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('ExpressValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $res = $this->expressService->saveService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function update(Request $request, $id)
    {
        $param = $request->param();
        $validate = validate('ExpressValidate');
        if(!$validate->scene('update')->check($param)) {
            return json(['code'=> 401, 'type'=> 'update', 'msg'=> $validate->getError()]);
        }
        $res = $this->expressService->updateService($param, $id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->expressService->deleteService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
}
