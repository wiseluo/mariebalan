<?php

namespace app\api\service;

class ForumService extends BaseService
{
    public function indexService()
    {
        $data = model('api/ForumRepository', 'repository')->forumList();
        foreach($data as $k => $v) {
            $data[$k]['pic'] = common_func_pic_domain($v['pic']);
        }
        return $data;
    }

}