<?php
namespace Admin\Controller;

use Think\Controller;

class IndexController extends AdminController
{
    public function index()
    {
        $menuTree = menuToTree(C('MENU'));

        $this->assign('menus', $menuTree);
        $this->display();
    }

    public function welcome()
    {
        $this->display();
    }
}
