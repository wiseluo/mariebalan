<?php
namespace app\admin\controller;

use app\admin\controller\Admin;
use app\admin\model\Userinfo;
use \think\Image;
use think\facade\Session;
use think\facade\Env;

class Upload extends Admin
{
	public function index()
	{
		$url = request()->url(true);
		$query = substr($url,strpos($url,'?')+1);
		return '<form id="form1" name="form1" method="post" action="/admin/upload/upload?'.$query.'" enctype="multipart/form-data">
  					<input name="file" type="file" id="pic" value="" onchange="form1.submit()" style="width:100%"/>
				</form>';
	}

	public function upload()
	{	
		//上传目录
		$up_dir = input('get.updir');
		//图片显示区域id
		$pic_area = input('get.picarea');
		//显示上传后文件名的input标签id
		$in_pic = input('get.inpic');
		//是否显示文件 false不显示  true显示
		$is_show = input('get.isshow');
		//允许显示多少 single只允许显示一个  more允许显示多个
		$show_num = input('get.shownum');
		//图片显示宽度
		$pw = (input('get.pw/d'))?:80;
		//图片显示高度
		$ph = (input('get.ph/d'))?:80;
		//是否直接在img标签中显示  传入的是该标签的id
		$img_id = input('get.imgid');
		//显示什么样的风格
		$show_style = input('get.style');

		$file = request()->file('file');
		$error = $_FILES['file']['error'];
		if ($error) {
			return alert_error('文件上传失败！',1,'/admin/upload?'.request()->query());
		}
		//原文件名
		$or_filename = $file -> getInfo()['name'];
		//获取文件扩展名
		$or_ext = strtolower(substr($or_filename, strrpos($or_filename, '.')+1));
		//return alert_success('上传目录：'.Env::get('root_path').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'products',1,'/admin/upload/',30);
		$updir = DIRECTORY_SEPARATOR.'uploads';
		if ($up_dir) {
			$updir .= DIRECTORY_SEPARATOR.$up_dir;
			$up_dir = Env::get('root_path').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$up_dir;
		} else {
			$up_dir = Env::get('root_path').'public'.DIRECTORY_SEPARATOR.'uploads';
		}
		if (!is_dir($up_dir)) {
            mkdir($up_dir,0777,true);
        }
        //return alert_success('上传后目录：'.$up_dir,1,'/admin/upload/',10); 
        //这里设置了上传文件大小为10M=10485760B
		$info = $file->validate(['size'=>10485760,'ext'=>'jpg,png,gif,jpeg,pdf,txt,doc,docx,xls,xlsx'])->move($up_dir,date('Ym').DIRECTORY_SEPARATOR.uniqid().'.'.$or_ext);
		if($info){
	        // 成功上传后 获取上传信息
			//return alert_success('上传后文件名为：'.$info->getFilename(),1,'/admin/upload/',10);
			if (in_array($or_ext, ["jpg","png","gif","jpeg"])) {
				$image = Image::open($up_dir.DIRECTORY_SEPARATOR.$info->getSaveName());
				// 生成一个350*350的缩略图并保存
				$image->thumb(350, 350,Image::THUMB_CENTER)->save($up_dir.DIRECTORY_SEPARATOR.$info->getSaveName().'.350x350.jpg');
				// 生成一个160*160的缩略图并保存
				$image->thumb(160, 160,Image::THUMB_CENTER)->save($up_dir.DIRECTORY_SEPARATOR.$info->getSaveName().'.160x160.jpg');
				// 生成一个最大为$pw*$ph的缩略图并保存
				$image->thumb($pw, $ph,Image::THUMB_CENTER)->save($up_dir.DIRECTORY_SEPARATOR.$info->getSaveName().'.thumb.jpg');
				// 生成一个40*40的缩略图并保存
				$image->thumb(40, 40,Image::THUMB_CENTER)->save($up_dir.DIRECTORY_SEPARATOR.$info->getSaveName().'.40x40.jpg');

			}
			
	        $str ='<script type="text/javascript" src="/static/admin/js/jquery-1.11.2.min.js"></script> <script type="text/javascript" src="/static/admin/js/layer/layer.js"></script><script type="text/javascript" src="/static/admin/js/upclass.js"></script>';
	        $str .= '<script type="text/javascript">';

	        if ($img_id) {
	        	$admin = Session::get('admin');
	        	Userinfo::where('id', $admin->id)->update(['pic'=>$info->getSaveName()]);
	        	$str .= '$("#'.$img_id.'",parent.document).prop("src","'.$updir.'/'.$info->getSaveName().'");';
	        	$str .= '</script>';
	        	echo $str;
	       		return alert_success('上传成功',1,'/admin/upload?'.request()->query(),1);
	        }

	        if ($show_num == 'single') {
	        	$str .= '$("#'.$in_pic.'",parent.document).val("'.$info->getSaveName().'");
	        			var file = "'.$info->getSaveName().'";';
	        } else {
	        	$str .= 'var fp = $("#'.$in_pic.'",parent.document).val();
	        			 var file = fp;
	        			if (fp == "") {
	        				$("#'.$in_pic.'",parent.document).val("'.$info->getSaveName().'");
	        				file = "'.$info->getSaveName().'";
	        			} else {
	        				$("#'.$in_pic.'",parent.document).val(fp+"|'.$info->getSaveName().'");
	        				file = fp+"|'.$info->getSaveName().'";
	        			}';
	        }
	        if ($is_show == 'false') {
	        	$str .= '</script>';
	        } else {
	        	if ($show_style == 1) {
	        		$top= floor(($ph-20)/2);
	        		$str .= 'var fp_arr = file.split("|");
	        			 $("#'.$pic_area.'",parent.document).find("div.tabbox").remove();
	        			 $.each(fp_arr,function(key,value){
	        			 	var indexof = value.lastIndexOf(".");
	        			 	var ext = value.substr(indexof+1);
	        			 	if ($.inArray(ext, ["jpg","png","gif","jpeg"]) != -1){
	        			 		$("#'.$pic_area.'",parent.document).append(\''
	        			 		.'<div class="tabbox" style="width:'.$pw.'px; height:'.$ph.'px;">'
	        			 		   .'<img id="img\'+key+\'" src="'.$updir.'/\'+value+\'.thumb.jpg"  width="'.$pw.'"  height="'.$ph.'"/>'
	        			 		   .'<div class="imgbg" style="width:'.$pw.'px; height:'.$ph.'px;">'
	        			 		   		.'<a style="position: absolute;cursor:pointer;left:3px;top:'.$top.'px"><i title="查看" onclick="view(\'+key+\')" class="fa fa-eye" style="color: #fff"></i></a>'
	        			 		   		.'<a style="position: absolute;cursor:pointer;right:3px;top:'.$top.'px"><i title="删除" onClick="delFile1(\'+key+\',\\\''.$in_pic.'\\\')" class="fa fa-trash-o" style="color: #fff"></i></a>'
	        			 		   .'</div>'
	        			 		.'</div>'
	        			 		.'\')
	        			 	} else {
	        			 		$("#'.$pic_area.'",parent.document).append(\''
	        			 		.'<div class="tabbox" style="width:'.$pw.'px; height:'.$ph.'px;">'
	        			 		   .'<img id="img\'+key+\'" src="/static/admin/images/file.jpg"  width="'.$pw.'"  height="'.$ph.'"/>'
	        			 		   .'<div class="imgbg" style="width:'.$pw.'px; height:'.$ph.'px;">'
	        			 		   		.'<a style="position: absolute;cursor:pointer;right:0"><i title="删除" onClick="delFile1(\'+key+\',\\\''.$in_pic.'\\\')" class="fa fa-trash-o" style="color: #fff"></i></a>'
	        			 		   .'</div>'
	        			 		.'</div>'
	        			 		.'\')
	        			 	}
	        			 	
	        			 })';

	        	} else {
	        		$str .= 'var fp_arr = file.split("|");
	        			 $("#'.$pic_area.'",parent.document).find("a").remove();
	        			 $("#'.$pic_area.'",parent.document).find("img").remove();
	        			 $.each(fp_arr,function(key,value){
	        			 	var indexof = value.lastIndexOf(".");
	        			 	var ext = value.substr(indexof+1);
	        			 	if ($.inArray(ext, ["jpg","png","gif","jpeg"]) != -1){
	        			 		$("#'.$pic_area.'",parent.document).append(\''
	        			 		.'<a id="a\'+key+\'" style="position: absolute;cursor:pointer"><i  title="删除" onClick="delFile(\'+key+\',\\\''.$in_pic.'\\\')" class="fa fa-trash-o"></i></a>'
	        			 		.'<img id="img\'+key+\'" src="'.$updir.'/\'+value+\'.thumb.jpg"  width="'.$pw.'"  height="'.$ph.'" style="margin-top:3px;display:inline-block;padding-top:6px;padding-left:7px"/>'.'\')
	        			 	} else {
	        			 		$("#'.$pic_area.'",parent.document).append(\''
	        			 		.'<a id="a\'+key+\'" style="position: absolute;cursor:pointer"><i  title="删除" onClick="delFile(\'+key+\',\\\''.$in_pic.'\\\')" class="fa fa-trash-o"></i></a>'
	        			 		.'<img id="img\'+key+\'" src="/static/admin/images/file.jpg"  width="'.$pw.'" height="'.$ph.'" style="margin-top:3px;display:inline-block;padding-top:6px;padding-left:7px"/>'.'\')
	        			 	}
	        			 	
	        			 })';
	        	}
	        	$str .= '</script>';
	        }
	       echo $str;
	       return alert_success('上传成功',1,'/admin/upload?'.request()->query(),1);
	        
	    }else{
	        // 上传失败获取错误信息
	        return alert_error($file->getError(),1,'/admin/upload?'.request()->query());
	        
	    }

		
	}
}