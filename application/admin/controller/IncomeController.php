<?php

namespace app\admin\controller;

use think\Request;
use app\admin\service\IncomeService;

class IncomeController extends BaseController
{
    public function __construct(IncomeService $incomeService)
    {
        parent::__construct();
        $this->incomeService = $incomeService;
    }

    public function index(Request $request)
    {
        $param['page_size'] = $request->param('page_size',10);
        $param['style'] = $request->param('style', 0);
        $param['keyword'] = $request->param('keyword', '');
        $param['sdate'] = $request->param('sdate', '');
        $param['edate'] = $request->param('edate', '');

        $list = $this->incomeService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function read($id)
    {
        $res = $this->incomeService->readService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('IncomeValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $res = $this->incomeService->saveService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function update(Request $request, $id)
    {
        $param = $request->param();
        $validate = validate('IncomeValidate');
        if(!$validate->scene('update')->check($param)) {
            return json(['code'=> 401, 'type'=> 'update', 'msg'=> $validate->getError()]);
        }
        $res = $this->incomeService->updateService($param, $id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->incomeService->deleteService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
}
