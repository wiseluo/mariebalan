<?php

namespace app\api\repository;

use app\api\model\Comment;

class CommentRepository
{
    public function get($id)
    {
        return Comment::get($id);
    }

    public function find($where)
    {
        return Comment::where($where)->find();
    }

    public function select($where=[])
    {
        return Comment::where($where)->select()->toArray();
    }

    public function save($data, $field = true) {
        $comment = new Comment($data);
        $res = $comment->allowField($field)->save();
        if($res){
            return $comment->id;
        }else{
            return 0;
        }
    }
    
    public function update($data, $where, $field = true)
    {
        $comment = new Comment();
        return $comment->allowField($field)->save($data, $where);
    }

    public function softDelete($where)
    {
        return Comment::destroy($where);
    }

    public function forceDelete($where)
    {
        return Comment::where($where)->delete();
    }
    
    //恢复恢复数据的数据
    public function restore($where)
    {
        $comment = new Comment();
        return $comment->restore($where);
    }

    public function hitsInc($id)
    {
        return Comment::where('id', $id)->setInc('hits', 1);
    }

    public function commentList($param)
    {
        $where[] = ['Comment.state', '=', 1];
        $where[] = ['Comment.agreement', '=', 1];

        return Comment::alias('Comment')
            ->join('yw_user User', 'User.id=Comment.user_id')
            ->field('Comment.id,Comment.user_id,Comment.photos,Comment.content,Comment.anonymous,User.nickname,User.headimgurl')
            ->where($where)
            ->order('Comment.id desc')
            ->paginate($param['page_size'])
            ->toArray();
    }

    public function getCommentDetail($id)
    {
        return Comment::alias('c')
            ->join('yw_order_goods g', 'g.id=c.order_goods_id', 'left')
            ->field('c.id,g.title,g.pic goods_pic,g.price,g.num,c.attrs,c.content,c.photos,c.hits,c.create_time')
            ->where('c.id', $id)
            ->find();
    }

    public function userCommentedList($param)
    {
        $where[] = ['Comment.state', '=', 1];
        $where[] = ['Comment.agreement', '=', 1];
        $where[] = ['Comment.user_id', '=', $param['user_id']];

        return Comment::alias('Comment')
            ->join('yw_goods Goods', 'Goods.id=Comment.goods_id')
            ->field('Comment.id,Comment.order_goods_id,Comment.user_id,Comment.attrs,Comment.star,Comment.photos,Comment.content,Comment.anonymous,Comment.reply_content,Goods.title,Goods.pics')
            ->where($where)
            ->order('Comment.id desc')
            ->paginate($param['page_size'])
            ->toArray();
    }
}
