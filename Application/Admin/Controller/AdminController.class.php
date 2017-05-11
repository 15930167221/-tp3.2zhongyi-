<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Auth;

class AdminController extends Controller
{
    protected $uid;
    protected $mod;

    protected function _initialize()
    {
        $this->uid = session('uid');
        $this->checkLogin();
        $this->activeMenu();
        $this->checkPermission();
    }

    public function checkLogin()
    {
        if(empty($this->uid)) {
            $this->redirect('Public/login');
        }
    }
    //首页右侧头部导航
    public function activeMenu()
    {
        $actData = activeTree(C('MENU'), cookie('active'));
        //var_dump(cookie('active'));
        $this->assign('location', $actData);
        $this->assign('active', end($actData));
    }



    public function checkPermission()
    {
        $auth = new Auth();

        if (!$auth->check(MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME, 1)) {
           die('您没有此权限');
        }
    }
}



