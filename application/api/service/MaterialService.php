<?php

namespace app\api\service;

class MaterialService extends BaseService
{
    public function indexService()
    {
        $data = model('api/MaterialRepository', 'repository')->materialList();
        foreach($data as $k => $v) {
            $data[$k]['pic'] = common_func_pic_domain($v['pic']);
        }
        return $data;
    }

}