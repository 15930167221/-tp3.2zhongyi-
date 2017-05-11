<?php
namespace Home\Controller;
use Think\Controller;
class JiezhenController extends PublicController {
    //接诊区今日明日等
   protected function __initialize()
    {
        parent::_initialize();
    }
   
    //明日
    public function mingri(){

        // 区分是否是接诊区页面
        $cid = I('get.cid');
        $this->assign('qufenjiez',$cid);
        // 法一自己写的附带样式
        $rect = M('station_p');
        $tjri = date('Y-m-d',strtotime("+1day"));//获取明天日期
        $where['p_date'] =array('between',"$tjri 00:00:00,$tjri 23:59:59");
        $where['jz_flag'] = 1;
        //获取所属机构
        $dpment = session('dpment');
        $where['department'] = $dpment;
        $count = $rect->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
        $page = getpage($count,9);//控制页面显示条数
        $show = $page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        //以上是分页 ， 以下是数据
        //'jz_flag=1'
        $data =  $rect->where($where)->order('p_date desc')->limit($page->firstRow.','.$page->listRows)->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,nl as nlt,tel,br_id,xh')->select();//查询数据（未完成就诊的）$Page->firstRow 起始条数 $Page->listRows 获取多少条
        $this->assign('data',$data);// 赋值模板变量
        $this->display();
    }
    //后日
    public function houri(){

        // 区分是否是接诊区页面
        $cid = I('get.cid');
        $this->assign('qufenjiez',$cid);
         // 法一自己写的附带样式
        $rect = M('station_p');
        $tjri = date('Y-m-d',strtotime("+2day"));//获取后天日期
        $where['p_date'] =array('between',"$tjri 00:00:00,$tjri 23:59:59");
        $where['jz_flag'] = 1;
        //获取所属机构
        $dpment = session('dpment');
        $where['department'] = $dpment;
        $count = $rect->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
        $page = getpage($count,9);//控制页面显示条数
        $show = $page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        //以上是分页 ， 以下是数据
        //'jz_flag=1'
        $data =  $rect->where($where)->order('p_date desc')->limit($page->firstRow.','.$page->listRows)->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,nl as nlt,tel,br_id,xh')->select();//查询数据（未完成就诊的）$Page->firstRow 起始条数 $Page->listRows 获取多少条
        $this->assign('data',$data);// 赋值模板变量
        $this->display();
    }
    //上周
    public function shangzhou(){
        
        // 区分是否是接诊区页面
        $cid = I('get.cid');
        $this->assign('qufenjiez',$cid);
         // 法一自己写的附带样式
        $rect = M('station_p');
        $tjri = date('Y-m-d',strtotime("-1day"));//昨天
        $dotjri = date('Y-m-d',strtotime("-7day"));//前七天
        $where['p_date'] =array('between',"$dotjri 00:00:00,$tjri 23:59:59");
        $where['jz_flag'] = 1;
        //获取所属机构
        $dpment = session('dpment');
        $where['department'] = $dpment;
        $count = $rect->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
        $page = getpage($count,9);//控制页面显示条数
        $show = $page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        //以上是分页 ， 以下是数据
        //'jz_flag=1'
        $data =  $rect->where($where)->order('p_date desc')->limit($page->firstRow.','.$page->listRows)->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,nl as nlt,tel,br_id,xh')->select();//查询数据（未完成就诊的）$Page->firstRow 起始条数 $Page->listRows 获取多少条
        // echo $rect->getLastSql();

        $this->assign('data',$data);// 赋值模板变量
        $this->display();
    }
}