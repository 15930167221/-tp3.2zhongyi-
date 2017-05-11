<?php
/**
 * Created by PhpStorm.
 * User: Miracle7kill
 * Date: 2017/3/1
 * Time: 9:15
 */
namespace Home\Controller;

use Think\Controller;

class PublicController extends Controller
{
    protected $logtime;

    public function _initialize()
    {
        $this->logtime = session('logintime');
        $this->checkLoginSession();
    }

    public function checkLoginSession()
    {
        $nowTime = $_SERVER['REQUEST_TIME'];
//        echo $nowTime;
        if (($nowTime - $this->logtime) > 600) {
            $user = M('user_info_dict');
            $user->where(array('id' => session('wh_userId')))->save(array('online' => 0, 'lastime' => time()));
            session_unset();
            $url = U('Indexs/index');
            echo "<script>alert('长时间无操作,请重新登录')</script>";
            echo "<script>parent.location.href='$url'</script>";
            //$this->redirect('Index/index');
        } else {
            session('logintime', $nowTime);
        }
    }
}