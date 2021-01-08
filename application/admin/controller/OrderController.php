<?php

namespace app\admin\controller;

use think\Request;
use app\admin\service\OrderService;

class OrderController extends BaseController
{
    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $param['page_size'] = $request->param('page_size',10);
        $param['keyword'] = $request->param('keyword', '');
        $param['state'] = $request->param('state', 0);

        $list = $this->orderService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function read($id)
    {
        $res = $this->orderService->readService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function delete($id)
    {
        $res = $this->orderService->deleteService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function toDeliverOrderList(Request $request)
    {
        $param['page_size'] = $request->param('page_size',10);
        $param['keyword'] = $request->param('keyword', '');

        $list = $this->orderService->toDeliverOrderListService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function deliverOrder(Request $request)
    {
        $param = $request->param();
        $validate = validate('OrderValidate');
        if(!$validate->scene('deliverOrder')->check($param)) {
            return json(['code'=> 401, 'type'=> 'deliverOrder', 'msg'=> $validate->getError()]);
        }
        $res = $this->orderService->deliverOrderService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
}
