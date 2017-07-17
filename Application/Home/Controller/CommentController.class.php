<?php

namespace Home\Controller;

use Think\Controller;

class CommentController extends Controller
{
    public function index()
    {
        $comment_text = strip_tags($_POST['comment_text']);
        $user_id = $_POST['user_id'];
        $news_id = $_POST['news_id'];

        $create_time = time();
        $data = array(
            'comment_text' => $comment_text,
            'user_id' => $user_id,
            'news_id' => $news_id,
            'create_time' => $create_time
        );

        if (empty($comment_text)) {
            return show(0, '请输入合法的评论格式！');
        }

        // TODO
        $result = D('Comment')->add($data);

        if ($result) {
            return show(1, '评论成功！');
        } else {
            return show(0, '评论失败！');
        }
    }

    public function refresh()
    {
        $news_id = intval($_POST['news_id']);

        $result = D('Comment')->findCommentByNewsId($news_id);

        $data = array();
        if ($result) {
            foreach ($result as $item) {
                $user_name = D('User')->findUserNameById($item['user_id']);
                $item['user_name'] = $user_name;
                array_push($data, $item);
            }
            return show(1, '刷新成功！', $data);
        }
    }

    public function updateCommentLikeCount()
    {
        $result = D('Comment')->updateCommentLikeCountById($_POST['comment_id'], $_POST['comment_like_count']);

        if ($result == 1) {
            return show(1, '👍');
        } elseif ($result == 0) {
            return show(0, '👎');
        }
    }
}