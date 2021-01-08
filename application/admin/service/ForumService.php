<?php

namespace app\admin\service;

class ForumService extends BaseService
{
    public function indexService($param)
    {
        return $this->ForumRepository->forumList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'state' => $value['state'],
                'name' => $value['name'],
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
        $forum = $this->ForumRepository->get($id);
        if($forum){
            return ['status' => 1, 'msg'=> '成功', 'data'=> $forum];
        }else{
            return ['status' => 0, 'msg'=> '品类不存在'];
        }
    }

    public function saveService($param)
    {
        $forum_data = [
            'state' => $param['state'],
            'name' => $param['name'],
            'pic' => $param['pic'],
            'sort' => $param['sort'],
        ];
        $forum_id = $this->ForumRepository->save($forum_data);
        if($forum_id) {
            return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $forum_id];
        }else{
            return ['status' => 0, 'msg' => '添加失败'];
        }
    }

    public function updateService($param, $id)
    {
        $forum_data = [
            'state' => $param['state'],
            'name' => $param['name'],
            'pic' => $param['pic'],
            'sort' => $param['sort'],
        ];
        $forum_res = $this->ForumRepository->update($forum_data, ['id'=> $id]);
        if($forum_res) {
            return ['status'=> 1, 'msg'=> '修改成功', 'data'=> $forum_res];
        }else{
            return ['status' => 0, 'msg' => '修改失败'];
        }
    }

    public function deleteService($id)
    {
        $forum = $this->ForumRepository->get($id);
        if($forum) {
            $res = $this->ForumRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '品类不存在'];
        }
    }

}