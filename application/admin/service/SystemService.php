<?php

namespace app\admin\service;

class SystemService extends BaseService
{
    public function changePasswordService($param)
    {
        $admin = $this->AdminRepository->get($param['admin_id']);
        if($admin) {
            if($admin['password'] != md5($param['old_password'])) {
                return ['status' => 0, 'msg' => '旧密码输入错误'];
            }
            $admin_res = $this->AdminRepository->update(['password'=> md5($param['password'])], ['id'=> $param['admin_id']]);
            if($admin_res) {
                return ['status'=> 1, 'msg'=> '修改成功'];
            }else{
                return ['status' => 0, 'msg' => '修改失败'];
            }
        }else{
            return ['status' => 0, 'msg' => '账户错误'];
        }
    }

    public function adminInfoService($admin_id)
    {
        $admin = $this->AdminRepository->get($admin_id);
        if($admin) {
            return ['status'=> 1, 'msg'=> '成功', 'data'=> ['id'=> $admin['id'], 'username'=> $admin['username']]];
        }else{
            return ['status' => 0, 'msg' => '账户错误'];
        }
    }
}