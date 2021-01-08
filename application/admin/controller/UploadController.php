<?php

namespace app\admin\controller;

use think\Image;
use think\Request;

class UploadController extends BaseController
{

    /**
    * 上传视频
    *
    * @param  \think\Request $request
    * @return \think\Response
    */
    public function uploadVideo() {
        //获取文件
        $file = request()->file('file');
        if (!$file) {
            return json(['code' => 401, 'msg' => '文件必填']);
        }

        //创建文件
        $basePath = config('upload_file') .'video';
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777);
        }
        $info = $file->validate(['ext' => 'flv,wmv,rmvb,mp4,avi,mov,rm'])->move($basePath, 'indexvideo');
        if (empty($info)) {
            return json(['code' => 401, 'msg' => $file->getError()]);
        }
        $file_path = '/uploads/video/'. $info->getSaveName();
        //$pathName = $basePath . DIRECTORY_SEPARATOR . $info->getSaveName();
        $video_ads = model('admin/AdsRepository', 'repository')->find([['type', '=', 2]]);
        if($video_ads) {
            model('admin/AdsRepository', 'repository')->update(['url'=> $file_path], ['type'=> 2]);
        }else{
            model('admin/AdsRepository', 'repository')->save(['type'=> 2, 'title'=> '首页视频', 'url'=> $file_path, 'pic'=> '']);
        }
        return json(['code' => 200, 'msg' => '上传成功', 'data' => ['file_path'=> common_func_domain() . $file_path]]);
    }

    /**
    * 通用上传图片
    *
    * @param  \think\Request $request
    * @return \think\Response
    */
    public function uploadImage(Request $request) {
        // 获取上传文件
        $file = $request->file('file');
        if (!$file) {
            return json(['code' => 401, 'msg' => '文件必填']);
        }
        
        $ym = date('Ym', time());
        $upload_path = config('upload_file') .'admin/image/'. $ym;
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777);
        }
        $info = $file->validate(['ext' => 'png,jpg,jpeg,gif,bmp'])->rule('uniqid')->move($upload_path);
        if ($info) {
            // 成功上传后 获取上传信息
            $filename = $info->getFilename();
            $file_path = $upload_path .'/'. $filename;
            $extension = $info->getExtension();
            //dump($file_path);
            $image_thumb = Image::open($file_path);
            $image_thumb->thumb(200, 200)->save($upload_path .'/'. $filename . '.thumb.' . $extension);
            $image_50 = Image::open($file_path);
            $image_50->thumb(50, 50)->save($upload_path .'/'. $filename . '.small.' . $extension);
            return json(['code' => 200, 'msg' => '上传成功', 'data' => '/uploads/admin/image/'. $ym .'/'. $filename]);
        } else {
            // 上传失败获取错误信息
            return json(['code' => 401, 'msg' => $file->getError()]);
        }
    }
}
