<?php

namespace app\user\controller;

use think\Request;

class IncomeController extends BaseController
{
    public function logList(Request $request)
    {
        $param['page_size'] = $request->param('page_size',10);
        $param['style'] = $request->param('style', 0);
        $param['user_id'] = $request->user()['id'];
        $param['sdate'] = $request->param('sdate', '');
        $param['edate'] = $request->param('edate', '');

        $list = model('user/IncomeLogRepository', 'repository')->logList($param);
        return json(['code'=> 200, 'msg'=> '成功', 'data'=> $list]);
    }
    
}
