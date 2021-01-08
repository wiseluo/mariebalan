<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin;
use think\facade\Session;

class Login extends Controller
{
	protected $_G = ['sitename'=>'跨境洋货'];
	public function index()
	{
		$this->view->engine->layout(false);
		$info = ['username'=>'', 'password'=>''];
		return $this->fetch('login',['dateY' => date('Y'), '_G' => $this->_G, 'info'=>$info, 'msg'=>'']);
	}

	public function login()
	{
		if (request()->isPost()) {
			//获取相关登录的数据
            $data = input('post.info/a');
            // 通过用户名 获取 用户相关信息
            // 严格的判定
            $ret = Admin::get(['username'=>$data['username']]);

            if (!$ret) {
            	$msg = '该用户名不存在，或该用户还未被审核通过';
            	return $this->fetch('login',['dateY'=>date('Y'), '_G'=>$this->_G, 'info'=>$data, 'msg'=>$msg]);
            }
            if ($ret->password != md5($data['pwd'])) {
                $msg = '密码不正确';
                return $this->fetch('login',['dateY'=>date('Y'), '_G'=>$this->_G, 'info'=>$data, 'msg'=>$msg]);
            }
            //Admin::where('id', $ret->id)->update(['logins' => ($ret->logins+1), 'lastip' => request()->ip(), 'lasttime' => time()]);
            // 保存用户信息  
            Session::set('admin', $ret);
            return $this->redirect('/admin');
		} else {
			// 获取session
            $admin = Session::get('admin');
            if ($admin && $admin->id) {
            	return $this->redirect('/admin');
            }
		}
		return $this->redirect('/admin/login');
	}

	public function quit()
	{
		// 删除保存在session中的用户信息
		Session::delete('admin');
		//调到登录页面
		return $this->redirect('/admin/login');
	}
}