<?php

namespace app\api\controller;

use think\Controller;

class CitysController extends Controller
{
    public function cityList()
    {
        $fid = request()->param('fid', 0);
        $citys = model('api/CitysRepository', 'repository')->select([['fid', '=', $fid]]);
        return json(['code' => 200, 'msg'=>'', 'data' => $citys]);
    }

}
