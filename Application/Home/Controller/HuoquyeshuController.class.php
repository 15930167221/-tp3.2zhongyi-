<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:08
 */
namespace Home\Controller;
use Think\Controller;

class HuoquyeshuController extends Controller
{
    public function dangri(){
        $blh = I('post.blh');//患者病历号
        $sblhxh = I('post.sblhxh');//患者序号
        $tjri = date('Y-m-d',time());//获取当天日期
        $user = M();
        
        //获取所属机构
        $dpment = session('dpment');
        $sql = "select t.rn from (select *,row_number() over(order by isnull(jz_date,p_date) ASC) rn from STATION_P WHERE department = '$dpment' AND jz_flag = '1' AND p_date BETWEEN '$tjri 00:00:00' AND '$tjri 23:59:59') t where t.br_id='$blh' AND t.xh='$sblhxh'";
        $data = $user->query($sql);
        $b = 9;
        $yeshu = $data['0']['rn']/$b;
        $quzhengyeshu = ceil($yeshu);
        $this->ajaxReturn($quzhengyeshu);
    }
    public function mingri(){
        $blh = I('post.blh');//患者病历号
        $sblhxh = I('post.sblhxh');//患者序号
        $tjri = date('Y-m-d',strtotime("+1day"));//获取明天日期
        $user = M();

        //获取所属机构
        $dpment = session('dpment');
        $sql = "select t.rn from (select *,row_number() over(order by isnull(jz_date,p_date) ASC) rn from STATION_P WHERE department = '$dpment' AND jz_flag = '1' AND p_date BETWEEN '$tjri 00:00:00' AND '$tjri 23:59:59') t where t.br_id='$blh' AND t.xh='$sblhxh'";
        $data = $user->query($sql);
        $b = 9;
        $yeshu = $data['0']['rn']/$b;
        $quzhengyeshu = ceil($yeshu);
        $this->ajaxReturn($quzhengyeshu);
    }
    public function houri(){
        $blh = I('post.blh');//患者病历号
        $sblhxh = I('post.sblhxh');//患者序号
        $tjri = date('Y-m-d',strtotime("+2day"));//获取后天日期
        $user = M();

        //获取所属机构
        $dpment = session('dpment');
        $sql = "select t.rn from (select *,row_number() over(order by isnull(jz_date,p_date) ASC) rn from STATION_P WHERE department = '$dpment' AND jz_flag = '1' AND p_date BETWEEN '$tjri 00:00:00' AND '$tjri 23:59:59') t where t.br_id='$blh' AND t.xh='$sblhxh'";
        $data = $user->query($sql);
        $b = 9;
        $yeshu = $data['0']['rn']/$b;
        $quzhengyeshu = ceil($yeshu);
        $this->ajaxReturn($quzhengyeshu);
    }
    public function shangzhou(){
        $blh = I('post.blh');//患者病历号
        $sblhxh = I('post.sblhxh');//患者序号
        $tjri = date('Y-m-d',strtotime("-1day"));//昨天
        $dotjri = date('Y-m-d',strtotime("-7day"));//前七天
        $user = M();
        //获取所属机构
        $dpment = session('dpment');
        $sql = "select t.rn from (select *,row_number() over(order by isnull(jz_date,p_date) ASC) rn from STATION_P WHERE department = '$dpment' AND jz_flag = '1' AND p_date BETWEEN '$dotjri 00:00:00' AND '$tjri 23:59:59') t where t.br_id='$blh' AND t.xh='$sblhxh'";
        $data = $user->query($sql);
        $b = 9;
        $yeshu = $data['0']['rn']/$b;
        $quzhengyeshu = ceil($yeshu);
        $this->ajaxReturn($quzhengyeshu);
    }
    //判断接诊日期是否为空
    public function pdjzdate(){
        $br_id = session('id');
        $xh = session('xh');
        $dpment = session('dpment');
        $where['br_id'] = $br_id;
        $where['xh'] = $xh;
        $where['department'] = $dpment;
        $user = M('station_p')->where($where)->field('jz_date')->select();
        $this->ajaxReturn($user);
    }
}