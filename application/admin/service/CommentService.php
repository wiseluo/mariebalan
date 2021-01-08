<?php

namespace app\admin\service;

class CommentService extends BaseService
{
    public function indexService($param)
    {
        return $this->CommentRepository->commentList($param);
    }

    public function readService($id)
    {
        $comment = $this->CommentRepository->getCommentDetail($id);
        if($comment) {
            return ['status' => 1, 'msg'=> '成功', 'data'=> $comment];
        }else{
            return ['status' => 0, 'msg'=> '评论不存在'];
        }
    }

    // public function saveService($param)
    // {
    //     $comment_data = [
    //         'name' => $param['name'],
    //     ];
    //     $comment_id = $this->CommentRepository->save($comment_data);
    //     if($comment_id) {
    //         return ['status'=> 1, 'msg'=> '添加成功', 'data'=> $comment_id];
    //     }else{
    //         return ['status' => 0, 'msg' => '添加失败'];
    //     }
    // }

    public function updateService($param, $id)
    {
        $comment_data = [
            'state' => $param['state'],
        ];
        $comment_res = $this->CommentRepository->update($comment_data, ['id'=> $id]);
        if($comment_res) {
            return ['status'=> 1, 'msg'=> '修改成功', 'data'=> $comment_res];
        }else{
            return ['status' => 0, 'msg' => '修改失败'];
        }
    }

    public function deleteService($id)
    {
        $comment = $this->CommentRepository->get($id);
        if($comment) {
            $res = $this->CommentRepository->softDelete(['id'=> $id]);
            if($res) {
                return ['status'=> 1, 'msg'=> '删除成功'];
            }else{
                return ['status'=> 0, 'msg'=> '删除失败'];
            }
        }else{
            return ['status'=> 0, 'msg'=> '评论不存在'];
        }
    }

}