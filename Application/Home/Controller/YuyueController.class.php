<?php
namespace Home\Controller;
use Think\Controller;
class YuyueController extends PublicController {
    protected function __initialize()
    {
        parent::_initialize();
    }
	//预约人数ajax
	public function ajaxrsyy(){
         $user = M('station_p');// 实例化Data数据模型
        $date =I('get.date');//获取页面预约日期(含有时分秒)
        $dodate = date("Y-m-d",strtotime($date));//转换为只有年月日
        $where['p_date'] =array('between',"$dodate 00:00:00,$dodate 23:59:59");
        //获取数据->where("p_date like '". $times."%'")"reserve=2 and p_date like '".$yuyuedtime."%' "
        $where["reserve"]= 2;
        $dpment = session('dpment');
        $where['department'] = $dpment;
        $data = $user->where($where)->field('convert(varchar(19),p_date,120) as p_date,br_name')->order('p_date')->select();
        $b = array();
        $c = 0;
        foreach ($data as $v) {
            // $a[] .= $v['p_date'];
            $a = abs(strtotime($date)-strtotime($v['p_date']));
            //判断预约时间是否小于15分钟
            if($a<900){
                $p_date = $v['p_date'];
                $br_name = $v['br_name'];
                $qufyyrq = 1;
                //创建数组
                $result = compact("p_date", "br_name","qufyyrq");
                $b[] = $result;
                $c++;
            }else{
                $p_date = $v['p_date'];
                $br_name = $v['br_name'];
                $qufyyrq = 2;
                //创建数组
                $result = compact("p_date", "br_name","qufyyrq");
                $b[] = $result;
            };
          
        };
        $b['0'][]=$c;
        // $aaa = $user->getLastSql();
        $this->ajaxReturn($b,'json');
	}
	// 患者预约
    public function yuyue(){
      $br_name = I('post.br_name');
      $xb = I('post.xb');
      $tel = I('post.tel');
      $ghf = I('post.ghf');
        //判断挂号费
        if(empty($br_name)){
            //重定向到预约
            $this->redirect('Index/yuyue', array('cwxinxi' => "姓名未填写"));
            die;
        }else if(empty($xb)){
            //重定向到预约
            $this->redirect('Index/yuyue', array('cwxinxi' => "性别未填写"));
            die;
        }else{
            // 获取所属机构
            $dpment = session('dpment');
            $station = M('station_p');
            // echo 1;die;
            //判断是否是复诊
            $br_id = I('post.br_id');
            // dump($br_id);die;
            // 查出就诊几次
            $where['br_id']=$br_id;
            $where['department']=$dpment;
            $pdfuzhen = $station->where($where)->count();
            // dump($pdfuzhen);die;int(1)
            // 加一为当前就诊次数
            $dangqingjiuzhengcishu = $pdfuzhen+1;
            // dump($dangqingjiuzhengcishu);die;
            $data = I('post.');//获取数据
            // dump($data);die;
            $data['xh'] = $dangqingjiuzhengcishu;
            $data['reserve'] = 2;
            //获取登入人id  uid
           //获取登入人id  uid
            $uid = session('wh_userId');
            
            $data['operator'] = $uid;
            $data['department'] = $dpment;
            // dump($data);die;
            $station->add($data);//添加数据
            // dump($data);die;
            $this->redirect('Index/jiezhen');//重定向到接诊区
        }
          
    }
}