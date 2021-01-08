<?php

namespace app\user\service;

use think\Db;

class UserService extends BaseService
{
    //用户余额支付
    public function orderAccountPayService($param)
    {
        $order = $this->OrderRepository->get($param['order_id']);
        if (!$order || $order->state != 1) {
            return ['status'=> 0, 'msg'=> '订单状态异常'];
        }
        if((strtotime($order['create_time']) + 2400) < time()) { //超40分钟，失效
            $this->OrderRepository->update(['state'=> 5], ['id'=> $param['order_id']]);
            return ['status'=> 0, 'msg'=> '订单已失效，请重新下单'];
        }
        
        Db::startTrans();
        $user_id = request()->user()['id'];
        $user_dec_res = $this->userMoneyDec($user_id, $order['pay_money'], 2, '用户下单');
        if($user_dec_res['status'] == 0) {
            Db::rollback();
            return ['status' => 0, 'msg'=> $user_dec_res['msg']];
        }

        $pay_data = [
            'pay_type' => 0, //余额支付
            'order_no' => $order['number'],
            'trade_no' => '',
            'money' => $order['pay_money'],
        ];
        $order_res = model('user/PayService', 'service')->orderPaySuccess($pay_data);
        if($order_res['status'] == 0) {
            Db::rollback();
            return ['status' => 0, 'msg'=> '支付失败-'. $order_res['msg']];
        }
        Db::commit();
        return ['status' => 1, 'msg'=> '支付成功'];
    }

    //用户增加余额
    public function userMoneyInc($user_id, $change_money, $type, $title)
    {
        Db::startTrans();
        $user = $this->UserRepository->findByLock([['id', '=', $user_id]]);
        $user_money = bcadd($user['money'], $change_money, 2);
        $user_res = $this->UserRepository->update(['money'=> $user_money], ['id'=> $user_id]);
        if(!$user_res) {
            Db::rollback();
            return ['status' => 0, 'msg'=> '增加用户余额失败'];
        }
        $account_log_res = model('user/AccountLogService', 'service')->accountLogService($type, 1, $user_id, $change_money, $user_money, $title);
        if($account_log_res['status'] == 0) {
            Db::rollback();
            return ['status' => 0, 'msg'=> $account_log_res['msg']];
        }
        Db::commit();
        return ['status' => 1, 'msg'=> '增加用户余额成功'];
    }

    //用户减少余额
    public function userMoneyDec($user_id, $change_money, $type, $title)
    {
        Db::startTrans();
        $user = $this->UserRepository->findByLock([['id', '=', $user_id]]);
        if($user['money'] < $change_money) {
            Db::rollback();
            return ['status'=> 0, 'msg'=> '用户余额不足'];
        }
        $user_money = bcsub($user['money'], $change_money, 2);
        $user_res = $this->UserRepository->update(['money'=> $user_money], ['id'=> $user_id]);
        if(!$user_res) {
            Db::rollback();
            return ['status' => 0, 'msg'=> '扣除用户余额失败'];
        }
        $account_log_res = model('user/AccountLogService', 'service')->accountLogService($type, 2, $user_id, $change_money, $user_money, $title);
        if($account_log_res['status'] == 0) {
            Db::rollback();
            return ['status' => 0, 'msg'=> $account_log_res['msg']];
        }
        Db::commit();
        return ['status' => 1, 'msg'=> '扣除用户余额成功'];
    }

    //用户增加收益
    public function userIncomeInc($user_id, $change_income, $type, $title)
    {
        Db::startTrans();
        $user = $this->UserRepository->findByLock([['id', '=', $user_id]]);
        $user_income = bcadd($user['income'], $change_income, 2);
        $user_res = $this->UserRepository->update(['income'=> $user_income], ['id'=> $user_id]);
        if(!$user_res) {
            Db::rollback();
            return ['status' => 0, 'msg'=> '增加用户收益失败'];
        }
        $income_log_res = model('user/IncomeLogService', 'service')->incomeLogService($type, 1, $user_id, $change_income, $user_income, $title);
        if($income_log_res['status'] == 0) {
            Db::rollback();
            return ['status' => 0, 'msg' => $income_log_res['msg']];
        }
        Db::commit();
        return ['status' => 1, 'msg'=> '增加用户收益成功'];
    }

    //用户减少收益
    public function userIncomeDec($user_id, $change_income, $type, $title)
    {
        Db::startTrans();
        $user = $this->UserRepository->findByLock([['id', '=', $user_id]]);
        if($user['income'] < $change_income) {
            Db::rollback();
            return ['status'=> 0, 'msg'=> '用户收益不足'];
        }
        $user_income = bcsub($user['income'], $change_income, 2);
        $user_res = $this->UserRepository->update(['income'=> $user_income], ['id'=> $user_id]);
        if(!$user_res) {
            Db::rollback();
            return ['status' => 0, 'msg'=> '扣除用户收益失败'];
        }
        $income_log_res = model('user/IncomeLogService', 'service')->incomeLogService($type, 2, $user_id, $change_income, $user_income, $title);
        if($income_log_res['status'] == 0) {
            Db::rollback();
            return ['status' => 0, 'msg' => $income_log_res['msg']];
        }
        Db::commit();
        return ['status' => 1, 'msg'=> '扣除用户收益成功'];
    }

    //成交新用户分销 referee_id推荐人id、new_user_id新用户id
    public function dealNewUser($referee_id, $new_user_id)
    {
        $coupon_res = model('user/UserCouponService', 'service')->saveService($new_user_id, 1);
        if($coupon_res['status'] == 0) {
            return ['status'=> 0, 'msg'=> '添加优惠券失败'];
        }
        $user = $this->UserRepository->get($referee_id);
        if($user['vip_id'] == 2) { //会员用户
            $user_income_res = $this->userIncomeInc($referee_id, 50, 1, '成功推荐好友');
            if($user_income_res['status'] == 0) {
                return ['status' => 0, 'msg'=> $user_income_res['msg']];
            }
        }
        return ['status'=> 1, 'msg'=> '用户分销成功'];
    }

    //提升会员等级
    public function upgradeVip($user_id, $money)
    {
        $res = $this->UserRepository->spendMoneyInc($user_id, $money);
        if($res) {
            $user = $this->UserRepository->get($user_id);
            $vip = $this->VipRepository->getLastVip($user['spend_money']);
            if($vip && $vip['id'] != $user['vip_id']) {
                $res = $this->UserRepository->update(['vip_id'=> $vip['id']], ['id'=> $user_id]);
                if($res) {
                    return ['status' => 1, 'msg' => '提升会员等级成功'];
                }else{
                    return ['status' => 0, 'msg' => '提升会员等级失败'];
                }
            }
            return ['status' => 1, 'msg' => '会员等级不用提升'];
        }else{
            return ['status' => 0, 'msg' => '用户花费的金额累计失败'];
        }
    }

    //用户消费-推荐人分销返现
    public function payRebate($user_id, $money)
    {
        $user = $this->UserRepository->get($user_id);
        if(!$user['referee_id']) {
            return ['status' => 1, 'msg' => '没有推荐人，不返现'];
        }
        $referee = $this->UserRepository->get($user['referee_id']);
        if($referee['vip_id'] < 3) {
            return ['status' => 1, 'msg' => '推荐人为会员用户，不返现'];
        }
        $vip = $this->VipRepository->get($referee['vip_id']);
        if(!$vip) {
            return ['status' => 0, 'msg' => '推荐人会员等级不存在'];
        }
        $rebate = bcmul($money, $vip['rebate_rate'], 2);
        $rebate = ($rebate < 50) ? 50 : $rebate; //最低返50

        $user_income_res = $this->userIncomeInc($user['referee_id'], $rebate, 1, '用户消费返现');
        if($user_income_res['status'] == 0) {
            return ['status' => 0, 'msg'=> $user_income_res['msg']];
        }
        return ['status' => 1, 'msg' => '分销返现成功'];
    }
}