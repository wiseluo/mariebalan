<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\admin\model\News;

class Notice extends Base
{
	//公告显示
    public function notice()
    {
		$id = input('get.id');
    	$news = News::get($id);
    	$news_content = $news->content;	
        $news_title = $news->title; 
        $news_addtime = $news->addtime; 
    	//dump($news_content);exit;
    	$this->assign('news_content',$news_content);
        $this->assign('news_title',$news_title);
        $this->assign('news_addtime',$news_addtime);
    	return $this->fetch('notice/notice');
    }
}