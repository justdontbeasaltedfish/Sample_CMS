<?php

namespace Common\Model;

use Think\Model;

class CommentModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('comment');
    }

    public function add($data)
    {
        return $this->_db->add($data);
    }

    public function findCommentByNewsId($news_id)
    {
        return $this->_db
            ->where('news_id=' . $news_id)
            ->order('create_time desc')
            ->select();
    }

    public function updateCommentLikeCountById($comment_id, $comment_like_count)
    {
        return $this->_db
            ->where('comment_id=' . $comment_id)
            ->save(array('comment_like_count' => $comment_like_count + 1));
    }
}