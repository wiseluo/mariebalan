<?php

namespace app\admin\service;

class AdsService extends BaseService
{
    public function indexService($param)
    {
        return $this->AdsRepository->adsSlideList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'state' => $value['state'],
                'title' => $value['title'],
                'pic' => $value['pic'],
                'url' => $value['url'],
                'sort' => $value['sort'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function readService($id)
    {
        $ads = $this->AdsRepository->get($id);
        if($ads){
            return ['status' => 1, 'msg'=> '成功', 'data'=> $ads];
        }else{
            return ['status' => 0, 'msg'=> '广告不存在'];
        }
    }

    public function saveService($param)
    {
        $ads_data = [
            'state' => $param['state'],
            'title' => $param['title'],
            'pic' => $param['pic'],
            'url' => $param['url'],
            'sort' => $param['sort'],
        ];
        $ads_id = $this->AdsRepository->save($ads_data);
        if($ads_id) {
            return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $ads_id];
        }else{
            return ['status' => 0, 'msg' => '添加失败'];
        }
    }

    public function updateService($param, $id)
    {
        $ads_data = [
            'state' => $param['state'],
            'title' => $param['title'],
            'pic' => $param['pic'],
            'url' => $param['url'],
            'sort' => $param['sort'],
        ];
        $ads_res = $this->AdsRepository->update($ads_data, ['id'=> $id]);
        if($ads_res) {
            return ['status'=> 1, 'msg'=> '修改成功', 'data'=> $ads_res];
        }else{
            return ['status' => 0, 'msg' => '修改失败'];
        }
    }

    public function deleteService($id)
    {
        $ads = $this->AdsRepository->get($id);
        if($ads) {
            $res = $this->AdsRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '广告不存在'];
        }
    }

}