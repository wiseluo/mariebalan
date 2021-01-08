<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use app\admin\model\News as NewsModel;
use think\Db;

class News extends Admin 
{
	public function newslist()
	{
		$dbs = NewsModel::order('id','desc')->paginate(10);
		$page = $dbs->render();
		$dbs = $dbs->toarray();
        if ($dbs) {
            disposeMoreFile($dbs['data'],'pic');
        }
        //dump($dbs);exit;
		$this->assign('dbs',$dbs);
		$this->assign('page',$page);
		$wk='';
		$this->assign('wk',$wk);
		return $this->fetch();
	}

    //增加和修改-页面
	public function newsadd()
	{
		//类型选择数组
		$arrtype = [1=>'商城公告',2=>'促销信息'];

		$id = input('get.id');
		$info = NewsModel::where('id',$id)->find();
		$files = '';
		if ($info['pic']) {
			$files = disposeFile($info['pic']);
		}
		//return $files;
		$this->assign('files',$files);
		$this->assign('info',$info);
		$this->assign('arrtype',$arrtype);
		//$this->assign('pdtype',$pdType);
		return $this->fetch();
	}

	//增加/修改记录
    public function add()
    { 
        //有id为修改
        $id = input('get.id');
        //dump($id);exit;
        $info = input('post.');
        //dump($info);exit;

        $mue = new NewsModel();
        if(0 != $id){    //修改
            $mue->allowField(true)->where('id',$id)->update($info);
        }else{
        	$info['addtime'] = time();
            $mue->allowField(true)->save($info);
        }
        return $this->rec;
    }


    //删除记录
    public function delete()
    {
        $id=input('get.id');
        $id=(int)$id;
        //dump($id);exit;
        $info=NewsModel::where('id',$id)->delete();
        return $this->rec;
    }

    //读取记录
	public function read()
    {
		$id=input('get.id');
		$info=NewsModel::where('id',$id)->find();
		$files='';
		if ($info['pic']) {
			$files = disposeFile($info['pic']);
		}
		$this->assign('files',$files);
		$this->assign('info',$info);
		$this->view->engine->layout(false);
        return $this->fetch('newsadd');
    }


    //搜索
	public function search()
	{
		$keyword = input('get.wk');
		if ($keyword == '') {
			$dbs = NewsModel::order('id','desc')->paginate(10);
		} else {
			$dbs = NewsModel::where('title','like',"%$keyword%")->order('id','desc')->paginate(10);
		}		
		$page = $dbs->render();
		$dbs = $dbs->toarray();
		//dump($dbs);exit;
        if ($dbs) {
            disposeMoreFile($dbs['data'],'pic');
        }
		$this->assign('wk',$keyword);
		$this->assign('dbs',$dbs);
		$this->assign('page',$page);
		return $this->fetch('newslist');
	}



	//批量删除
    public function dels()
    {
        $ids=input('post.IDS/a');
        
        if(is_array($ids))$info=NewsModel::where('id','in',$ids)->delete();
        
        return $this->rec;
    }
    
    //批量审核
    public function flag()
    {
        $ids=input('post.IDS/a');
        
        if(is_array($ids))$info=NewsModel::where('id','in',$ids)->update(array('flag'=>1));
        
        return $this->rec;
    }
    
    //取消审核
    public function canelflag()
    {
        $ids=input('post.IDS/a');
        
        if(is_array($ids))$info=NewsModel::where('id','in',$ids)->update(array('flag'=>0));
        
        return $this->rec;
    }
}