<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * use Common\Model 这块可以不需要使用，框架默认会加载里面的内容
 */
class LoginController extends Controller
{

    public function index()
    {
        if (session('adminUser')) {
            $this->redirect('/Sample_CMS/admin.php?c=index&a=index');
        }
        $this->display();
    }

    public function check()
    {
        $userName = $_POST['userName'];
        $password = $_POST['password'];

        if (!trim($userName)) {
            return show(0, '用户名不能为空！');
        }
        if (!trim($password)) {
            return show(0, '密码不能为空！');
        }

        $admin = D('Admin')->getAdminByUsername($userName);

        if (!$admin || $admin['status'] != 1) {
            return show(0, '该用户不存在！');
        }

        if ($admin['password'] != getMd5Password($password)) {
            return show(0, '密码错误！');
        }

        D("Admin")->updateByAdminId($admin['admin_id'], array('lastlogintime' => time()));

        session('adminUser', $admin);
        return show(1, '登录成功！');


    }

    public function loginout()
    {
        session('adminUser', null);
        $this->redirect('/Sample_CMS/admin.php?c=login&a=index');
    }

}