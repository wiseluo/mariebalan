<?php

namespace app\api\service;

use think\Db;

class CommentService extends BaseService
{
    public function indexService($param)
    {
        $list = $this->CommentRepository->commentList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'user_id' => $value['user_id'],
                'content' => $value['content'] == '' ? '此用户未填写评价内容' : $value['content'],
                'photos' => common_func_pics_domain($value['photos']),
                'anonymous' => $value['anonymous'],
                'nickname' => $value['anonymous'] == 1 ? common_func_anonymous_nickname($value['nickname']) : $value['nickname'],
                'headimgurl' => $value['anonymous'] == 1 ? '' : $value['headimgurl'],
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }

    public function readService($id)
    {
        $comment = $this->CommentRepository->getCommentDetail($id);
        if($comment) {
            $comment['photos'] = common_func_pics_domain($comment['photos']);
            $this->CommentRepository->hitsInc($id);
            return ['status' => 1, 'msg'=> '成功', 'data'=> $comment];
        }else{
            return ['status' => 0, 'msg'=> '评论不存在'];
        }
    }

    public function saveService($param)
    {
        $comment = $this->CommentRepository->find([['user_id', '=', $param['user_id']], ['order_goods_id', '=', $param['order_goods_id']]]);
        if($comment) {
            return ['status'=> 0, 'msg'=> '该商品已评价'];
        }
        $order_goods = $this->OrderGoodsRepository->get($param['order_goods_id']);
        if($order_goods == null) {
            return ['status'=> 0, 'msg'=> '订单商品不存在'];
        }
        $comment_data = [
            'user_id' => $param['user_id'],
            'order_goods_id' => $param['order_goods_id'],
            'order_id' => $order_goods['order_id'],
            'goods_id' => $order_goods['goods_id'],
            'attrs' => $order_goods['attrs'],
            'photos' => $param['photos'],
            'content' => $param['content'],
            'anonymous' => $param['anonymous'],
            'agreement' => $param['agreement'],
        ];
        $comment_res = $this->CommentRepository->save($comment_data);
        if($comment_res) {
            $this->OrderGoodsRepository->update(['comment_state'=> 2], ['id'=> $param['order_goods_id']]);
            return ['status'=> 1, 'msg'=> '评论成功'];
        }else{
            return ['status'=> 0, 'msg'=> '评论失败'];
        }
    }

    public function commentReplyService($param)
    {
        $comment = $this->CommentRepository->get($param['comment_id']);
        if($comment == null) {
            return ['status'=> 0, 'msg'=> '该评价已删除'];
        }
        $comment_reply_data = [
            'comment_id' => $param['comment_id'],
            'content' => $param['content'],
        ];
        $comment_reply_res = $this->CommentReplyRepository->save($comment_reply_data);
        if($comment_reply_res) {
            return ['status'=> 1, 'msg'=> '评论成功'];
        }else{
            return ['status'=> 0, 'msg'=> '评论失败'];
        }
    }

    public function userCommentedService($param)
    {
        $list = $this->CommentRepository->userCommentedList($param);
        $data = array_map(function($value) {
            $item = [
                'id' => $value['id'],
                'order_goods_id' => $value['order_goods_id'],
                'user_id' => $value['user_id'],
                'title' => $value['title'],
                'goods_pics' => common_func_pics_domain($value['pics'], '/uploads/products/'),
                'attrs' => $value['attrs'],
                'content' => $value['content'] == '' ? '您没有填写评价内容' : $value['content'],
                'photos' => common_func_pics_domain($value['photos']),
                'reply_content' => $value['reply_content'],
                'anonymous' => $value['anonymous'] == 1 ? '已匿名' : '',
            ];
            return $item;
        }, $list['data']);
        $list['data'] = $data;
        return $list;
    }
}