<?php

namespace app\user\controller;

use think\Request;
use app\user\service\UserService;
use app\common\common\JwtTool;

class UserController extends BaseController
{
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }
    
    public function userinfo(Request $request)
    {
        //$this->user['refereenum'] = 0;//推荐人数
        //$this->user['historynum'] = Db::table('yw_historys')->where('uid',$this->user['id'])->count();
        // $this->user['refundnum'] = Db::table('yw_orders')->where('refund',1)->where(['uid'=>$this->user['id']])->count();
        $user_id = $request->user()['id'];
        $user = model('user/UserRepository', 'repository')->userinfo($user_id);
        $user['sex'] = ($user['sex'] == 1 ? '男' : '女');
        $user['unpaid_num'] = model('api/OrderRepository', 'repository')->getOrderNums([['user_id', '=', $user_id], ['state', '=', 1]]);
        $user['undelivered_num'] = model('api/OrderRepository', 'repository')->getOrderNums([['user_id', '=', $user_id], ['state', '=', 2]]);
        $user['unreceived_num'] = model('api/OrderRepository', 'repository')->getOrderNums([['user_id', '=', $user_id], ['state', '=', 3]]);
        $user['uncommented_num'] = model('api/OrderGoodsRepository', 'repository')->getOrderGoodsNums([['user_id', '=', $user_id], ['comment_state', '=', 1]]);

        $coupons_count = model('user/UserCouponRepository', 'repository')->userCouponsCount($user_id);
        $user['coupons_count'] = $coupons_count[0]['coupons_count'];
        $vip = model('user/VipRepository', 'repository')->get($user['vip_id']);
        $user['vip'] = ['name'=> $vip['name'], 'pic'=> common_func_pic_domain($vip['pic'])];

        return json(['code' => 200, 'msg'=>'', 'data' => $user]);
    }

    public function updateHeadimgurl(Request $request)
    {
        $param = $request->param();
        $validate = validate('UserValidate');
        if(!$validate->scene('updateHeadimgurl')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = model('user/UserRepository', 'repository')->update(['headimgurl'=> $param['headimgurl']], ['id'=> $request->user()['id']]);
        if($res) {
            return json(['code'=> 200, 'msg'=> '修改成功']);
        }else{
            return json(['code'=> 401, 'msg'=> '修改失败']);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->param('token');
        $jwtTool = new JwtTool();
        $jwtTool->deleteToken($token);
        return json(['code'=> 200, 'msg'=> '登出成功']);
    }

    public function orderAccountPay(Request $request)
    {
        $param = $request->param();
        $validate = validate('UserValidate');
        if(!$validate->scene('orderAccountPay')->check($param)) {
            return json(['code'=> 401, 'msg'=> $validate->getError()]);
        }
        $res = $this->userService->orderAccountPayService($param);
        if($res['status']) {
            return json(['code'=> 200, 'msg'=> $res['msg'], 'data'=> []]);
        }else{
            return json(['code'=> 401, 'msg'=> $res['msg']]);
        }
    }
}
