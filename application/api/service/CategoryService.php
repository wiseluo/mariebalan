<?php

namespace app\api\service;

class CategoryService extends BaseService
{
    public function indexService()
    {
        $data = model('api/CategoryRepository', 'repository')->categoryList();
        foreach($data as $k => $v) {
            $data[$k]['pic'] = common_func_pic_domain($v['pic']);
        }
        return $data;
    }

}