<?php

namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function login()
    {
        $jump_url = $_SERVER['HTTP_REFERER'];

        $user_name = $_POST['user_name'];
        $user_password = $_POST['user_password'];

        if (empty($user_name)) {
            return show(0, '用户名不能为空！');
        }
        if (empty($user_password)) {
            return show(0, '密码不能为空！');
        }

        $result = D('User')->getUserDataByUsername($user_name);

        if (!$result) {
            show(0, '用户名不存在！');
        }
        if (!password_verify($user_password, $result['user_password_hash'])) {
            return show(0, '密码错误！');
        }
        if ($result['user_active'] != 1) {
            return show(0, '用户没有激活！');
        }
        if ($result['user_deleted'] == 1) {
            return show(0, '用户在小黑屋！');
        }

        session('userInfo', $result);
        return show(1, '登录成功！', array('url' => $jump_url));
    }

    public function logout()
    {
        session('userInfo', null);
        $url = $_SERVER['HTTP_REFERER'];
        header('Location:' . $url);
    }

    public function isLogin()
    {
        $user = session('userInfo');

        if ($user && is_array($user)) {
            return show(1, '用户已登录！', $user);
        } else {
            return show(0, '无登录用户！');
        }
    }
}