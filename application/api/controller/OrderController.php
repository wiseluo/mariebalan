<?php

namespace app\api\controller;

use think\Request;
use app\api\service\OrderService;

class OrderController extends BaseController
{
    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $param['page'] = $request->param('page',1);
        $param['page_size'] = $request->param('page_size',10);
        $param['state'] = $request->param('state',0); // 1待付款,2已付款,待发货,3待收货,已发货,4已收货,完成,5关闭
        $param['refund'] = $request->param('refund',0); // 售后:0正常,1退换处理中,2已完成退换,3未完成退换
        $param['user_id'] = $request->user()['id'];

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

    public function orderNowPrejudge(Request $request)
    {
        $param = $request->param();
        $validate = validate('OrderValidate');
        if(!$validate->scene('orderNowPrejudge')->check($param)) {
            return json(['code'=> 401, 'type'=> 'orderNowPrejudge', 'msg'=> $validate->getError()]);
        }
        $res = $this->orderService->orderNowPrejudgeService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function orderNow(Request $request)
    {
        $param = $request->param();
        $validate = validate('OrderValidate');
        if(!$validate->scene('orderNow')->check($param)) {
            return json(['code'=> 401, 'type'=> 'orderNow', 'msg'=> $validate->getError()]);
        }
        $list = $this->orderService->orderNowService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function shopcartSettlementPrejudge(Request $request)
    {
        $param = $request->param();
        $validate = validate('OrderValidate');
        if(!$validate->scene('shopcartSettlementPrejudge')->check($param)) {
            return json(['code'=> 401, 'type'=> 'shopcartSettlementPrejudge', 'msg'=> $validate->getError()]);
        }
        $param['user_id'] = $request->user()['id'];
        $res = $this->orderService->shopcartSettlementPrejudgeService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function shopcartSettlement(Request $request)
    {
        $param = $request->param();
        $validate = validate('OrderValidate');
        if(!$validate->scene('shopcartSettlement')->check($param)) {
            return json(['code'=> 401, 'type'=> 'shopcartSettlement', 'msg'=> $validate->getError()]);
        }
        $param['user_id'] = $request->user()['id'];
        $list = $this->orderService->shopcartSettlementService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function save(Request $request)
    {
        $param = $request->param();
        $validate = validate('OrderValidate');
        if(!$validate->scene('save')->check($param)) {
            return json(['code'=> 401, 'type'=> 'save', 'msg'=> $validate->getError()]);
        }
        $param['user_id'] = $request->user()['id'];
        $res = $this->orderService->saveService($param);
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

    public function orderRemindDeliver($id)
    {
        $res = $this->orderService->orderRemindDeliverService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function orderConfirmReceipt($id)
    {
        $res = $this->orderService->orderConfirmReceiptService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function orderCancel($id)
    {
        $res = $this->orderService->orderCancelService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    public function orderExpress($id)
    {
        $res = $this->orderService->orderExpressService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
    
    public function uncommentedList(Request $request)
    {
        $param['page'] = $request->param('page',1);
        $param['page_size'] = $request->param('page_size',10);
        $param['user_id'] = $request->user()['id'];
        $list = $this->orderService->uncommentedListService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }
}
