<?php

namespace app\api\service;

class AdsService extends BaseService
{
    public function adsSlideService()
    {
        $data = model('api/AdsRepository', 'repository')->adsSlideList();
        foreach($data as $k => $v) {
            $data[$k]['pic'] = common_func_pic_domain($v['pic']);
        }
        return $data;
    }

}