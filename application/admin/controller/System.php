<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31
 * Time: 14:01
 */
namespace app\admin\controller;

use app\admin\controller\Admin;
use app\admin\model\User;
use app\admin\model\Userinfo;
use app\admin\model\Feedback;
use app\admin\model\Shoppdtype;
use app\admin\model\City;
use app\admin\model\Country;
use think\Db;
class System extends Admin{
    /*
     * 密码修改
     */
    public function changepwd(){

        $user = new User;
        $pwd = input('post.pwd');
        $pwd1 = input('post.pwd1');
        $uid = input('post.uid');

        if($pwd!='' && $pwd1!=''){
            $pwddata = $user->where('id',$uid)->field('pwd')->find();
            if($pwddata['pwd'] == md5($pwd)){
                $newpwd['pwd'] = md5($pwd1);
                $pwds = $user->allowField(true)->where('id',$uid)->update($newpwd);
                if($pwds){
                    return "密码修改成功";
                }else{
                    return "密码修改失败";
                }
            }else{
                return "密码错误";
            }
        }else{
            return $this->fetch();
        }

    }

    public function changepaypwd(){

        $userinfo = new Userinfo;
        $pwd = input('post.pwd');
        $pwd1 = input('post.pwd1');
        $uid = input('post.uid');

        if($pwd!='' && $pwd1!=''){
            $pwddata = $userinfo->where('id',$uid)->field('paypwd')->find();
            if($pwddata['paypwd'] == md5($pwd)){
                $newpwd['paypwd'] = md5($pwd1);
                $pwds = $userinfo->allowField(true)->where('id',$uid)->update($newpwd);
                if($pwds){
                    return "密码修改成功";
                }else{
                    return "密码修改失败";
                }
            }else{
                return "密码错误";
            }
        }else{
            return $this->fetch();
        }

        return $this->fetch("changepwd");
    }

    /*
     * 评论管理
     */
    public function feedback(){
        $feeds = new Feedback;

        $wk = input('get.wk/a');
        if($wk)
        $wk = implode(',',$wk);
        $feedback = $feeds->where('name','like',"%$wk%")->paginate(10,false);
        //总共多少条
        $page = $feedback->total();
        //每页多少条
        $listRows = $feedback->listRows();
        //当前页
        $currentPage = $feedback->currentPage();
        $this->assign('currentPage',$currentPage);
        $this->assign('listRows',$listRows);
        $this->assign('page',$page);
        $this->assign('dbs',$feedback);
        $this->assign('wk',$wk);
        return $this->fetch();
    }
    public function feeddel(){
        $delid = input('get.id');
        $feed = new Feedback;
        $deldata = $feed->where('id',$delid)->delete();
        return $deldata;
    }
    public function dels(){
        $dels = input('post.IDS/a');
        $feed = new Feedback;
        $delsdata = $feed->where('id','in',$dels)->delete();
        return $this->rec;
    }
    public function flag(){
        $flags = input('post.IDS/a');
        $feed = new Feedback;
        $falgsdata = $feed->where('id','in',$flags)->update(array('flag'=>1));
        return $this->rec;
    }
    public function canelflag(){
        $canelfs = input('post.IDS/a');
        $feed = new Feedback;
        $canelfdata = $feed->where('id','in',$canelfs)->update(array('flag'=>0));
        return $this->rec;
    }

    /*
     * 城市管理
     */
    public function citylist(){
        $city = new City;
        $wk = input('get.wk/a');
        if($wk)
            $wk = implode(',',$wk);
        $citydata = $city->where('name','like',"%$wk%")->where('fid',0)->order('id','desc')->paginate(10);
        $citys = $city->where('name','like',"%$wk%")->where('fid','neq',0)->select()->toArray();
        $currentPage = $citydata->currentPage();
        $listRows = $citydata->listRows();
        $page = $citydata->total();
        $this->assign('page',$page);
        $this->assign('listRows',$listRows);
        $this->assign('currentPage',$currentPage);
        $this->assign('dbs',$citydata);
        $this->assign('wk',$wk);
        $this->assign('citys',$citys);
        return $this->fetch();
    }
    public function cityadd(){
        $city = new City;
        $id = input('get.id/d');
        $info = $city->where('id',$id)->find();
        $dbs = $city->where('fid',0)->select()->toArray();
        $citys = $city->where('fid','neq',0)->select()->toArray();
        $this->assign('dbs',$dbs);
        $this->assign('citys',$citys);
        $this->assign('info',$info);
        $this->view->engine->layout(false);
        return $this->fetch();
    }
    public function add(){
        $city = new City;
        $id = input('post.id');
        $info = input('post.info/a');
        if($id){
            $cityEdit = $city->allowField(true)->where('id',$id)->update($info);
        }else{
            $info['addtime'] = time();
            $cityAdd = $city->allowField(true)->save($info);
        }
    }
    public function del(){
        $city = new City;
        $delid = input('get.id');
        $del = $city->where('id',$delid)->delete();
        return $del;
    }

    public function delss(){
        $dels = input('post.IDS/a');
        $city = new City;
        $delsdata = $city->where('id','in',$dels)->delete();
        return $this->rec;
    }
    public function flags(){
        $flags = input('post.IDS/a');
        $city = new City;
        $falgsdata = $city->where('id','in',$flags)->update(array('flag'=>1));
        return $this->rec;
    }
    public function canelflags(){
        $canelfs = input('post.IDS/a');
        $city = new City;
        $canelfdata = $city->where('id','in',$canelfs)->update(array('flag'=>0));
        return $this->rec;
    }

    /*
     * 国家
     */
    public function countrylist(){
        $country = new Country;
        $wk = input('get.wk/a');
        if($wk)
            $wk = implode(',',$wk);
        $countrydata = $country->where('name','like',"%$wk%")->order('id','desc')->paginate(10);
        $currentPage = $countrydata->currentPage();
        $listRows = $countrydata->listRows();
        $page = $countrydata->total();
        $pages = $countrydata->render();
        $countrydata = $countrydata->toarray();
        if($countrydata){
            disposeMoreFile($countrydata['data'],'pic');
        }
        $this->assign('page',$page);
        $this->assign('pages',$pages);
        $this->assign('listRows',$listRows);
        $this->assign('currentPage',$currentPage);
        $this->assign('dbs',$countrydata);
        $this->assign('wk',$wk);
        return $this->fetch();
    }
    public function countryadd(){
        $country = new Country;
        $id = input('get.id/d');
        $info = $country->where('id',$id)->find();

        $files = '';
        if ($info['pic']) {
            $files = disposeFile($info['pic']);
        }


        $this->assign('files',$files);
        $this->assign('info',$info);
        $this->view->engine->layout(false);
        return $this->fetch();
    }
    public function countryaddlist(){
        $country = new Country;
        $id = input('post.id');
        $info = input('post.info/a');
        if($id){
            $countryEdit = $country->allowField(true)->where('id',$id)->update($info);
        }else{
            $info['addtime'] = time();
            $countryAdd = $country->allowField(true)->save($info);
        }
    }
    public function countrydel(){
        $country = new Country;
        $delid = input('get.id');
        $del = $country->where('id',$delid)->delete();
        return $del;
    }
    public function countrydelss(){
        $dels = input('post.IDS/a');
        $country = new Country;
        $delsdata = $country->where('id','in',$dels)->delete();
        return $this->rec;
    }
    public function countryflags(){
        $flags = input('post.IDS/a');
        $country = new Country;
        $falgsdata = $country->where('id','in',$flags)->update(array('flag'=>1));
        return $this->rec;
    }
    public function countrycanelflags(){
        $canelfs = input('post.IDS/a');
        $country = new Country;
        $canelfdata = $country->where('id','in',$canelfs)->update(array('flag'=>0));
        return $this->rec;
    }

}