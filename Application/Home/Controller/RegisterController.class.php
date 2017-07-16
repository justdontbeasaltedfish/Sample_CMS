<?php

namespace Home\Controller;

use Think\Controller;

class RegisterController extends Controller
{
    public function register()
    {
        $user_name = strip_tags($_POST['user_name']);
        $user_password_new = $_POST['user_password_new'];
        $user_password_repeat = $_POST['user_password_repeat'];
        $user_email = strip_tags($_POST['user_email']);

        if (empty($user_name)) {
            return show(0, '用户名不能为空！');
        }
        if (empty($user_password_new) OR empty($user_password_repeat)) {
            return show(0, '密码不能为空！');
        }
        if (empty($user_email)) {
            return show(0, '邮箱不能为空！');
        }

        // 验证用户名
        if (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $user_name)) {
            return show(0, '用户名格式不正确！');
        }
        $result = D('User')->getUserDataByUsername($user_name);
        if ($result) {
            return show(0, '用户名已存在！');
        }

        // 验证密码
        if (strlen($user_password_new) < 6) {
            return show(0, '密码格式不正确！');
        }
        if ($user_password_new !== $user_password_repeat) {
            return show(0, '两次输入的密码不相同！');
        }

        // 验证邮箱
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            return show(0, '邮箱格式不正确！');
        }
        $result = D('User')->doesEmailAlreadyExist($user_email);
        if ($result) {
            return show(0, '邮箱已存在！');
        }

        $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);
        $data = array(
            'user_name' => $user_name,
            'user_password_hash' => $user_password_hash,
            'user_email' => $user_email
        );
        $result = D('User')->writeNewUserToDatabase($data);
        if ($result) {
            return show(1, '注册成功！');
        } else {
            return show(0, '注册失败！');
        }
    }
}