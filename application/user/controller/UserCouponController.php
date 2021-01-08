<?php

namespace app\user\controller;

use think\Request;
use app\user\service\UserCouponService;

class UserCouponController extends BaseController
{
    public function __construct(UserCouponService $userCouponService)
    {
        parent::__construct();
        $this->userCouponService = $userCouponService;
    }

    public function index(Request $request)
    {
        $param['state'] = $request->param('state', 0);
        $param['user_id'] = $request->user()['id'];
        $list = $this->userCouponService->indexService($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }
}
