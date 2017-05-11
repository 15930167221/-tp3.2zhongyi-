<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/25
 * Time: 9:18
 */
namespace Home\COntroller;
use Think\Controller;

class IndexsController extends Controller
{
    public function index(){
        $a = I('get.aa');
        if($a == 1){
            $this->assign('msg','<b style="color:red">用户名或密码错误</b>');
            $this->display();
        }elseif($a == 2){
            $this->assign('msg','<b style="color:red">您的账号已登录</b>');
            $this->display();
        }else{
            $this->display();
        }
    }
    public function forget(){
        $this->display();
    }
}