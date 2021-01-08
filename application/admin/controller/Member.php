<?php
namespace app\admin\controller;
use app\admin\controller\Admin;

use app\admin\model\Acctype;
use app\admin\model\User;
use app\admin\model\Userinfo;
use app\admin\model\Group;
use app\admin\model;
use think\Db;
class Member extends Admin{
    public function userlist(){
        $wk = input("get.wk/a");
        $wk = implode(',',$wk);
        $vip = input("get.vip");
        $vips = Db::table('yw_vip')->column('name','id');
        if ($wk == '' && $vip == ''){
            $users = Db::table('yw_user')->order(['id'=>'desc'])->paginate(10);
        }else{
            $users = Db::table('yw_user')->where(
                function ($query) use($vip) {
                    if($vip > 0){
                        $query->where('vip_id',$vip);
                    }
                }
            )->where(
                function ($query) use($wk) {
                    if($wk != ''){
                        $query->where('nickname','like',"%$wk%");
                    }
                }
            )->order(['id'=>'desc'])->paginate(10);
        }
        $this->assign('wk',$wk);
        $this->assign('vip',$vip);
        $this->assign('vips',$vips);
        $this->assign('users',$users);
        return $this->fetch();
    }

    public function search(){
        $wk = input("get.wk/a");
        $tid = input("get.tid/a");
        $wk = implode(',',$wk);
        $tid = implode(',',$tid);
        $members=User::alias('user')
            ->join('userinfo','user.id=userinfo.id','LEFT')
            ->field('userinfo.*,user.*')
            ->where('userinfo.name','like',"%$wk%")
            ->where('userinfo.tid','like',"%$tid%")
            ->select()
            ->toArray();
        $this->assign('members',$members);
        return $this->fetch("userlist");


    }

    public function useradd(){

        $id=input('get.id/d');
        $info=User::alias('user')
            ->join('userinfo','user.id=userinfo.id','LEFT')
            ->field('user.*,userinfo.*')
            ->where('userinfo.id',$id)
            ->find();
        $files = '';
        if ($info['pic']) {
            $files = disposeFile($info['pic']);
        }
        $this->assign('files',$files);
//        print_r($info);exit;
        $this->assign('info',$info);
        $sexs = array(0=>'女',1=>'男');
        $this->assign('sexs',$sexs);

        //角色
        $groups=Group::select()->toArray();
        $this->assign('groups',$groups);

        //等级
        $levels = array(0=>'等级一',1=>'等级二',2=>'等级三');
        $this->assign('levels',$levels);

        //会员类型
        $tidtype=Acctype::select()->toArray();
        $this->assign('tidtype',$tidtype);


        //取消键入总框架
        $this->view->engine->layout(false);
        //载入模板
        return $this->fetch("useradd");
    }

    public function add(){

        //有id为修改
        $id = input('get.id');
        $userinfo = input('post.userinfo/a');
        $users = input('post.users/a');
        $users['pwd'] = md5($users['pwd']);
        $userinfo['paypwd'] = md5($userinfo['paypwd']);
        $users['addtime'] = date("Y-m-d H:i:s");
        if($id == ''){
            return 1;die;
            $userid = Db::table('yw_user')->allowField(true)->insertGetId($users);
            $userinfo['id'] = $userid;
            $datauser = Db::table('yw_userinfo')->allowField(true)->save($userinfo);
        }else{
            Db::table('yw_user')->allowField(true)->where('id',$id)->update($users);
            Db::table('yw_userinfo')->allowField(true)->where('id',$id)->update($userinfo);
        }
        return $datauser;

    }

    public function userdelete(){
        $id=input('get.id');
        $res = Db::table('yw_user')->where('id',$id)->delete();
        return $res;
    }

    public function dels(){
        $user = new User;
        $uinfo = new Userinfo;
        $delid = input('post.IDS/a');
        if(is_array($delid)){
            $delinfo=$user->where('id','in',$delid)->delete();
            if($delinfo){
                $delinfo=$uinfo->where('id','in',$delid)->delete();
                if($delinfo){
                    return "删除成功";
                }
            }

        }
    }

    public function flag(){
        $user = new User;
        $flagid = input('post.IDS/a');
        if(is_array($flagid)){
            $flaginfo=$user->where('id','in',$flagid)->update(array('flag'=>1));
            return $this->rec;
        }

    }

    public function canelflag(){
        $user = new User;
        $flagid = input('post.IDS/a');
        if(is_array($flagid)){
            $flaginfo=$user->where('id','in',$flagid)->update(array('flag'=>0));
            return $this->rec;
        }
    }


}