<?php

namespace app\api\controller;

use think\Db;
use think\Controller;

class Config extends Controller
{
    public function index()
    {
        $config = Db::table('yw_config')->column('*','skey');
        foreach ($config as $key => $value) {
            $json = json_decode($value['value'],true);
            if($json){
                $config[$key]['value'] = json_decode($value['value'],true);
            }
        }
        return json(array('code' => 200, 'data' => $config , 'msg'=>''));
    }

    public function create()
    {
        
    }

    public function save()
    {
        
    }

    public function read($id)
    {
        $value = Db::table('yw_config')->where('id',$id)->find();
        return json(array('code' => 200, 'data' => $value , 'msg'=>''));
    }


    public function edit($id)
    {
        
    }

    public function update($id)
    {
        
    }

    public function delete($id)
    {
        
    }
}
