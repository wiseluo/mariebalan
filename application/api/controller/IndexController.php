<?php

namespace app\api\controller;

use think\Request;
use think\Controller;

class IndexController extends Controller
{
    public function video(Request $request)
    {
        $video_ad = model('admin/AdsRepository', 'repository')->find([['type', '=', 2]]);
        if($video_ad) {
            return json(['code'=> 200, 'msg'=> '首页视频', 'data'=> ['url'=> common_func_pic_domain($video_ad['url'])]]);
        }else{
            return json(['code'=> 400, 'msg'=> '首页视频未上传']);
        }
    }
    
}
