<?php

namespace app\admin\repository;

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

    public function select($where = [], $field = true)
    {
        return Comment::field($field)->where($where)->select()->toArray();
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

    public function commentList($param)
    {
        if($param['keyword']) {
            $where[] = ['g.title', 'like', '%'. $param['keyword'] .'%'];
        }

        return Comment::alias('c')
            ->join('yw_order_goods g', 'g.id=c.order_goods_id', 'left')
            ->join('yw_user u', 'u.id=c.user_id', 'left')
            ->where($where)
            ->field('c.id,g.title,g.pic,u.nickname,c.state,c.content,c.create_time')
            ->select()
            ->toArray();
    }

    public function getCommentDetail($id)
    {
        return Comment::alias('c')
            ->join('yw_order_goods g', 'g.id=c.order_goods_id', 'left')
            ->join('yw_user u', 'u.id=c.user_id', 'left')
            ->field('c.id,g.title,g.pic goods_pic,g.price,g.num,u.nickname,c.attrs,c.anonymous,c.content,c.photos,c.create_time')
            ->where('c.id', $id)
            ->find();
    }
}
