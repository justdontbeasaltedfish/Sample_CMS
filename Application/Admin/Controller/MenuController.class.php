<?php
/**
 * 后台菜单相关
 */

namespace Admin\Controller;

use Think\Controller;
use Think\Exception;

class MenuController extends CommonController
{

    public function add()
    {
        if ($_POST) {
            if (!isset($_POST['name']) || !$_POST['name']) {
                return show(0, '菜单名不能为空！');
            }
            if (!isset($_POST['m']) || !$_POST['m']) {
                return show(0, '模块名不能为空！');
            }
            if (!isset($_POST['c']) || !$_POST['c']) {
                return show(0, '控制器不能为空！');
            }
            if (!isset($_POST['f']) || !$_POST['f']) {
                return show(0, '方法名不能为空！');
            }

            if ($_POST['menu_id']) {
                return $this->save($_POST);
            }
            $menuId = D("Menu")->insert($_POST);
            if ($menuId) {
                return show(1, '新增成功！', $menuId);
            }
            return show(0, '新增失败！', $menuId);

        } else {
            $this->display();
        }
    }

    public function index()
    {
        $data = array();
        if (isset($_REQUEST['type']) && in_array($_REQUEST['type'], array(0, 1))) {
            $data['type'] = intval($_REQUEST['type']);
            $this->assign('type', $data['type']);
        } else {
            $this->assign('type', -100);
        }
        /**
         * 分页操作逻辑
         */
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 5;

        $menus = D("Menu")->getMenus($data, $page, $pageSize);
        $menusCount = D("Menu")->getMenusCount($data);

        $thinkPage = new \Think\Page($menusCount, $pageSize);
        $pageResult = $thinkPage->show();
        $this->assign('pageResult', $pageResult);
        $this->assign('menus', $menus);
        $this->display();
    }

    public function edit()
    {
        $menuId = $_GET['id'];

        $menu = D("Menu")->find($menuId);
        $this->assign('menu', $menu);
        $this->display();
    }

    public function save($data)
    {
        $menuId = $data['menu_id'];
        unset($data['menu_id']);

        try {
            $id = D("Menu")->updateMenuById($menuId, $data);
            if ($id === false) {
                return show(0, '更新失败！');
            }
            return show(1, '更新成功！');
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }

    }

    public function setStatus()
    {
        try {
            if ($_POST) {
                $id = $_POST['id'];
                $status = $_POST['status'];
                // 执行数据更新操作
                $result = D("Menu")->updateStatusById($id, $status);
                if ($result) {
                    return show(1, '操作成功！');
                } else {
                    return show(0, '操作失败！');
                }

            }
        } catch (Exception $e) {
            return show(0, $e->getMessage());
        }

        return show(0, '没有提交的数据！');
    }

    public function listorder()
    {
        $listOrder = $_POST['listOrder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        if ($listOrder) {
            try {
                foreach ($listOrder as $menuId => $order) {
                    $result = D("Menu")->updateMenuListorderById($menuId, $order);
                    if ($result === false) {
                        $errors[] = $menuId;
                    }
                }
            } catch (Exception $e) {
                return show(0, $e->getMessage());
            }
            if ($errors) {
                return show(0, '排序失败！-' . implode(',', $errors));
            }
            return show(1, '排序成功', array('jump_url' => $jumpUrl));
        }
        return show(0, '排序数据失败！');
    }


}