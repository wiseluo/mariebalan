<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use think\Db;

class Ad extends Admin 
{
    public $types = [1=>'手机幻灯片'];

    public function adlist()
	{  
        $ads = Db::table('yw_ads')->order(['id'=>'desc'])->paginate(10);
        $this->assign('ads',$ads);
        $this->assign('types',$this->types);
        return $this->fetch();
    }

    //添加/修改记录-页面
    public function adadd()
    {
        //修改时id != 0
        $id = input('get.id');
        if($id > 0){
            $info = Db::table('yw_ads')->where('id',$id)->find();
            $this->assign('info',$info);
        }
        $this->assign('types',$this->types);
        $this->view->engine->layout(false);
        return $this->fetch('adadd');
    }

    //增加/修改记录
    public function add()
    { 
        //有id为修改
        $id = input('get.id');
        $info = request()->param('info/a');
        if($id > 0){
            Db::table('yw_ads')->where(['id'=>$id])->strict(false)->update($info);
        }else{
            Db::table('yw_ads')->strict(false)->insert($info);
        }
        $this->rec['msg'] = "保存成功";
        return $this->rec;
    }

}