<?php
namespace Home\Controller;

use Org\Util\Decrypt;
use Think\Controller;
class LoginController extends Controller {
    public function Login(){
        $user = M('user_info_dict');
        $about = M('about');
        //组合查询条件
        $where = array();
        // dump($_POST);
        $name = $_POST['name'];
        $pwd =md5($_POST['password']);
        $ip = get_client_ip();
        // dump($pwd);
        $where['code'] = $name;
        $where['passWord'] = $pwd;
        $result = $user->where($where)->find();
//         dump($result);die;
        //获取医疗机构名称
        $condition['id']=$result['department'];
        $aboutInf = $about->where($condition)->find();
        //读取机构配置文件
        $code = new Decrypt();
        $content = $code->decode(C('SALT'));
        $arr = explode(',', $content);
        //验证用户名密码对比
        switch ($result['online']) {
            case 0:
                if($result && $result['password'] == $pwd){
                    if(in_array($aboutInf['hospital'],$arr)){
                        if (($result['creatime'] - time()) > 0) {
                            $days = intval(($result['creatime'] - time())/86400);
                            $code = $result['code'];
                            //机构编码存入session
                            session('wh_code',$code);
                            $level = $result['power'];
                            $id = $result['id'];
                            $pow = $result['power'];
                            $path = $result['photopath'];
                            session('wh_power',$pow);
                            //用户名存入session
                            session('wh_userName',$result['username']);
                            session('wh_userId',$id);

                            session('logintime',time());
                            session('dpment',$result['department']);
                            session('ip',$ip);
                            session('days', $days);
                            session('userpwd',$result['password']);
                               $di = M('user_message');
                                $datat['userid'] = $id;
                                $datat['logtime'] = time();
                                $datat['name'] = session('wh_userName');
                                $datat['ip'] = $ip;

                                $di->add($datat);
                            //miracle7kill 修改登录状态online 1已登录 0未登录
                            $user->where(array('id' => $result['id']))->save(array('online' => 1, 'lastime' => time(), 'ip'=> $ip));
                            $this->redirect('index/home',array('rev'=>$level));
                        } else {
                            $this->error('您的试用期已过,请购买正式产品！',U('Indexs/index'),1);
                        }
                    }else{
                        $this->error('您暂时没有权限！',U('Indexs/index'),1);
                    }
                }else{
                    $this->redirect('indexs/index',array('aa'=>1));
                }
                break;
            case 1:
                if ((time() - $result['lastime']) >= 60) {
                    if($result && $result['password'] == $pwd){
                        if(in_array($aboutInf['hospital'],$arr)){
                            if (($result['creatime'] - time()) > 0) {
                                $days = intval(($result['creatime'] - time())/86400);
                                $code = $result['code'];
                                //机构编码存入session
                                session('wh_code',$code);
                                $level = $result['power'];
                                $id = $result['id'];
                                $pow = $result['power'];
                                session('wh_power',$pow);
                                //用户名存入session
                                session('wh_userName',$result['username']);
                                session('wh_userId',$id);
                                session('logintime',time());
                                session('dpment',$result['department']);
                                // var_dump($_SESSION);
                                session('ip',$ip);
                                session('days', $days);
                                session('userpwd',$result['password']);

                                $di = M('user_message');
                                $datat['userid'] = $id;
                                $datat['logtime'] = time();
                                $datat['name'] = session('wh_userName');
                                $datat['ip'] = $ip;

                                $di->add($datat);
                                //miracle7kill 修改登录状态online 1已登录 0未登录

                                $user->where(array('id' => $result['id']))->save(array('online' => 1, 'lastime' => time(), 'ip'=> $ip));
                                $this->redirect('index/home',array('rev'=>$level));
                            } else {
                                $this->error('您的试用期已过,请购买正式产品！',U('Indexs/index'),1);
                            }
                        }else{
                            $this->error('您暂时没有权限！',U('Indexs/index'),1);
                        }
                    }else{
                        $this->redirect('indexs/index',array('aa'=>1));
                    }
                } else {
                    $this->error('此账号已登录!');
                }
                break;
        }
    }
    //退出登录
    public function logOut(){
        $user = M('user_info_dict');
        $user->where(array('id' => session('wh_userId')))->save(array('online' => 0, 'logoutime' => time()));
        session_unset();
        // 清除session
        $this->redirect('indexs/index');
    }
    //更改密码
    public function upmima(){
        $br_name = I('post.name'); 
        $br_pass = I('post.password'); 
        $user = M("user_info_dict");
        $where['userName'] = $br_name;
        $data['passWord'] = $br_pass;
        $res = $user->where($where)->data($data)->save();
        // echo $user->getLastSql();die;
        $res == true ?  $this->redirect('indexs/index') : $this->error('修改失败');
        // dump($data);die;
    }
    
}