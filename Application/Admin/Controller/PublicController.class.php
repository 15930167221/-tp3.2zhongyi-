<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Verify;

class PublicController extends Controller
{
    public function login()
    {
        if (IS_POST) {
            $userN = I('post.username');
            $userP = I('post.password');
            $yzm = I('post.yzm');
            (empty($userN) || empty($userP)) && $this->error('用户名或密码不能为空');
            $veri = new Verify();
            (!$veri->check($yzm)) && $this->error('验证码错误');
            $res = D('Members')->login(array($userN, md5($userP)));

            if (!empty($res)) {
                $data = array();
                $data['last_logintime'] = time();
                $data['last_loginip'] = get_client_ip();
                D('Members')->where('id = %s', $res['id'])->save($data);
                session('uid', $res['id']);
                session('did', $res['department']);
                session('uname', $res['name']);
                session('lastime', $res['last_logintime']);
                session('lastip', $res['last_loginip']);
                $this->success('登录成功', U('Index/index'));
            } else {
                $this->error('用户名或密码错误');
            }
        } else {
            $this->display();
        }
    }

    public function loginout()
    {
        session('uid', NULL);
        $this->success('注销成功');
    }

    public function verify()
    {
        $veri = new Verify();
        $veri->fontSize = 18;
        $veri->length = 4;
        $veri->imageW = 123;
        $veri->imageH = 40;
        $veri->entry();
    }
}