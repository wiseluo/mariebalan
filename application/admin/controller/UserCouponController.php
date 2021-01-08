<?php

namespace app\admin\controller;

use think\Request;
use app\admin\service\UserCouponService;

class UserCouponController extends BaseController
{
    public function __construct(UserCouponService $userCouponService)
    {
        parent::__construct();
        $this->userCouponService = $userCouponService;
    }

    public function index(Request $request)
    {
        $param['page_size'] = $request->param('page_size',10);
        $param['keyword'] = $request->param('keyword', '');

        $list = $this->userCouponService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }

    public function read($id)
    {
        $res = $this->userCouponService->readService($id);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> $res['data']]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }

    
}
