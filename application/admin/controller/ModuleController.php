<?php

namespace app\admin\controller;

use think\Request;
use app\admin\service\ModuleService;

class ModuleController extends BaseController
{
    public function __construct(ModuleService $moduleService)
    {
        parent::__construct();
        $this->moduleService = $moduleService;
    }

    public function index(Request $request)
    {
        $param['page_size'] = $request->param('page_size',10);
        $param['keyword'] = $request->param('keyword', '');

        $list = $this->moduleService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function leftModule()
    {
        $list = $this->moduleService->leftModuleService();
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function read($id)
    {
        $res = $this->moduleService->readService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('ModuleValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $param['fid'] = $request->param('fid', 0);
        $res = $this->moduleService->saveService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function update(Request $request, $id)
    {
        $param = $request->param();
        $validate = validate('ModuleValidate');
        if(!$validate->scene('update')->check($param)) {
            return json(['code'=> 401, 'type'=> 'update', 'msg'=> $validate->getError()]);
        }
        $param['fid'] = $request->param('fid', 0);
        $res = $this->moduleService->updateService($param, $id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->moduleService->deleteService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
}
