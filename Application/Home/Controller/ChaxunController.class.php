<?php
namespace Home\Controller;

class ChaxunController extends PublicController {

    protected function __initialize()
    {
        parent::_initialize();
    }
    //收费综合查询
    public function sfzonghe(){
        $tyN = I('get.typeName');
        if($tyN!=1) {
             $this->display();
         } else {
            $data = I('get.');
            // dump($data);
            $where = array();
            // 判断不为空
            foreach ($data as $k => $v) {
                if ($v) {
                    $where[$k] = $v;
                }  
            }
            $startTime = $data['p_datekai'];
            $endTime = $data['p_datezhong'];
            //判断是否点击不含挂号费
            if ($data['sf_bfghf']== 'yes') {
                $where['item_code'] = array('NEQ','挂号费');
            }
            //拼接时间
            $where['charge_date'] = array('between',"$startTime 00:00:00,$endTime 23:59:59");
            $count =  M('v_sfmx')->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
            $page = getpage($count,7);//控制页面显示条数
            // 分页数据
            if (empty($data)) {
                    foreach ($data as $key => $val) {
                        $page->parameter[$key] = urlencode($val);
                    }
                }
            $show = $page->show();// 分页显示输出
            $this->assign('page',$show);// 赋值分页输出
            //以上是分页 ， 以下是数据
            $res = M('v_sfmx')->where($where)->limit($page->firstRow.','.$page->listRows)->field('convert(varchar(10),charge_date,120) as charge_date,invoice_no,blqsh,br_name,item_code,unit_price,amount,total,operator_code,bill_status,serial_no,convert(varchar(10),return_date,120) as return_date')->select();
            $aa = array();
            //判断退费日期是否为 1900-01-01
            foreach ($res as $key => $value) {
                if ($value['return_date'] == '1900-01-01') {
                    $value['return_date'] = '';
                    array_push($aa, $value);
                    // dump($aa);
                }else{
                    array_push($aa, $value);
                }
            }
            // var_dump($aa);
             // echo M('v_sfmx')->getLastSql();
            // 把查询信息带到页面
            $this->assign('dodata',$aa);
            //把查询条件带到页面
            $this->assign('cxtjdata',$data);
            // dump($data);
            //把操作员带到页面
            $user = M('user_info_dict');
            $czdata = $user->select();
            // dump($data);die;
            $this->assign('data',$czdata);
            $this->display();
         }
       
    }
    //费用汇总
    public function fyhuizong(){
        $this->display();
    }
    //费用汇总ajax
    public function fyhuizongAjax(){
        // 获取所属机构
            $dpment = session('dpment');
        $staTime=I('post.startTime');
        $endTime=I('post.endTime');
        $date=I('post.date');
        if($date=="year"){
            $fytj=M()->query("select convert(varchar(4),charge_date,120) charge_date,
                                case when a.item_code='挂号费' then '00' else a.item_code end item_code,
                                case when a.item_code='挂号费' then a.item_code else b.item_name end item_name,
                                sum(total) total
                                from v_sfmx a left join p_price_list b
                                on a.item_code=b.item_code
                                where a.charge_date>='$staTime 00:00:00' and a.charge_date<='$endTime 23:59:59'
                                and b.department = '$dpment'
                                group by convert(varchar(4),charge_date,120) ,
                                case when a.item_code='挂号费' then '00' else a.item_code end ,
                                case when a.item_code='挂号费' then a.item_code else b.item_name end
                                having sum(total)<>0
                                order by convert(varchar(4),charge_date,120) ,
                                case when a.item_code='挂号费' then '00' else a.item_code end ");
        }else if($date=="month"){
            $fytj=M()->query("select convert(varchar(7),charge_date,120) charge_date,
                                case when a.item_code='挂号费' then '00' else a.item_code end item_code,
                                case when a.item_code='挂号费' then a.item_code else b.item_name end item_name,
                                sum(total) total
                                from v_sfmx a left join p_price_list b
                                on a.item_code=b.item_code
                                where a.charge_date>='$staTime 00:00:00' and a.charge_date<='$endTime 23:59:59'
                                and b.department = '$dpment'
                                group by convert(varchar(7),charge_date,120) ,
                                case when a.item_code='挂号费' then '00' else a.item_code end ,
                                case when a.item_code='挂号费' then a.item_code else b.item_name end
                                having sum(total)<>0
                                order by convert(varchar(7),charge_date,120) ,
                                case when a.item_code='挂号费' then '00' else a.item_code end ");
        }else if($date=="day"){
            $fytj=M()->query("select convert(varchar(10),charge_date,120) charge_date,
                                case when a.item_code='挂号费' then '00' else a.item_code end item_code,
                                case when a.item_code='挂号费' then a.item_code else b.item_name end item_name,
                                sum(total) total
                                from v_sfmx a left join p_price_list b
                                on a.item_code=b.item_code
                                where a.charge_date>='$staTime 00:00:00' and a.charge_date<='$endTime 23:59:59'
                                and b.department = '$dpment'
                                group by convert(varchar(10),charge_date,120) ,
                                case when a.item_code='挂号费' then '00' else a.item_code end ,
                                case when a.item_code='挂号费' then a.item_code else b.item_name end
                                having sum(total)<>0
                                order by convert(varchar(10),charge_date,120) ,
                                case when a.item_code='挂号费' then '00' else a.item_code end ");
        }
        if($fytj){
            import('Org.Util.CoTable');
            $table = new \CoTable($fytj,'item_name','charge_date','total');
            $t=$table->RenderToTable();
        }else{
            $t="<table class=\"table table-striped\"><thead><tr><th width=\"50%\">日期</th><th width=\"50%\">合计</th></tr></thead><tbody><tr><td colspan='2' align='center'>暂时没有数据</td></tr></tbody></table>";
        }
        $this->ajaxReturn($t);
    }
    //药品统计
    public function yptongji(){
        // dump(I('get.'));
        $mod = M();
        $data = I('get.');
        $staTime=I('get.startTime');
        $endTime=I('get.endTime');
        $ypName=I('get.ypName');

        $ypName = $ypName ? $ypName : '%';

        $user_id = session('wh_userId');
        $dpId = session('dpment');
        $sql = "SET NOCOUNT ON
        DECLARE	@return_value int
EXEC	@return_value = [dbo].[PRO_COUNT_YP]
		@IP = $user_id,
		@startdate = N'$staTime',
		@enddate = N'$endTime',
		@drugname = N'$ypName',
		@dpId = $dpId
SELECT	'Return Value' = @return_value";
        $mod->query($sql);
        $count = M('count_yp')->where(array('IP' => $user_id))->count();
        $page = getpage($count, 7);
        // 分页数据
        if (empty($data)) {
            foreach ($data as $key => $val) {
                $page->parameter[$key] = urlencode($val);
            }
        }
        $show = $page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $yptj = M('count_yp')->where(array('IP' => $user_id))->limit($page->firstRow,$page->listRows)->order('id desc')->select();
        // 把查询信息带到页面
        $this->assign("yptj",$yptj);
        //把查询条件带到页面
        $this->assign("chaxtiaoj",$data);
        $this->display();
    }
    //药品统计查询ajax

    public function yptongjiInpAjax(){
        $val=I('post.value');
        $dpment = session('dpment');
        $res=M()->query("select drug_name,RIGHT(RTRIM(drug_spec),'1') AS units,drug_spec,jxmc,hl,dw
                    FROM drug_dict
                    LEFT JOIN sys_dm_jx ON drug_form = jxdm
                    LEFT JOIN sys_dm_jldw ON hl_unit = dwdm
                    WHERE drug_dict.department = '$dpment' and input_code like '%".$val."%' or drug_name like '%".$val."%'");
        $this->ajaxReturn($res);
    }
    //病历查询
    public function blchaxun(){
        $tyN = I('get.typeName');
        if($tyN!=1) {
            $this->display();
        } else {
            $chaxuntiaojian = I('get.');
            $this->assign('chaxuntiaojian',$chaxuntiaojian);
            $jbxx = M('station_p');
            $starttime = $_GET['starttime'];
            $endtime = $_GET['endtime'];
            $where = array();
            //判断是否为空
            if($_GET['blh'] != ''){$where['br_id'] = $_GET['blh'];}
            if($_GET['name'] != ''){$where['br_name'] = $_GET['name'];}
            if($_GET['idcard'] != ''){$where['pass'] = $_GET['idcard'];}
            if($_GET['sex'] != ''){$where['xb'] = $_GET['sex'];}
            //判断时间是否为空
            if($_GET['starttime'] != '' && $_GET['endtime'] != ''){
                $where['jz_date'] = array('between',"$starttime 00:00:00,$endtime 23:59:59");
            }
            $dangqian =date('Y-m-d');
            if($_GET['starttime'] != '' && $_GET['endtime'] == ''){
                $where['jz_date'] = array('between',"$starttime 00:00:00,$dangqian 23:59:59");
            } 
            $where['jz_flag'] = 2;
            //使用in 方法
            // $where['xh'] = 1;
            // 分页开始
            $count =  $jbxx->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
            $page = getpage($count,7);//控制页面显示条数
            // 分页数据
            if (empty($chaxuntiaojian)) {
                foreach ($chaxuntiaojian as $key => $val) {
                    $page->parameter[$key] = urlencode($val);
                }
            }
            $show = $page->show();// 分页显示输出
            $this->assign('page',$show);// 赋值分页输出
            //以上是分页 ， 以下是数据
            $res = $jbxx -> order('br_id desc') ->Distinct('br_id')-> where($where)->limit($page->firstRow.','.$page->listRows)->field('convert(varchar(19),p_date,120) as p_date,convert(varchar(19),jz_date,120) as jz_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,tel,br_id,dw,pass,e_mail,ghf,fax,convert(varchar(10),cs_date,120) as cs_date,department') -> select();
            $this -> assign('jbxx',$res);
            $this -> display();
        }     
        
    }
    //就诊记录
    public function jiuzhenjl(){
        $br_id = $_POST['br_id'];
        $jbxx = M('station_p');
        $res = $jbxx -> field('convert(varchar(19),jz_date,120) as jz_date') -> where("br_id = '$br_id' and jz_flag = 2") -> select();
        $this->ajaxReturn($res,'json');
    }
    public function jiuzhenls(){
        $jz_date = $_POST['jz_date'];//'2017-03-23 15:06:22';
        $dpment = $_POST['dpment'];//部门
        $jbxx = M('station_p');//病人基本信息表
        $jkda = M('jkda_xx');//健康档案信息表
        $bq = M('bqxx');//病情信息表
        $zy = M('Prescription');//中药处方表
        $zy_detail = M('prescription_detail');//中药明细表
        $xy = M('xydrugcf_detial');//西药明细表
        //查询基本信息
        $result = $jbxx -> where("jz_date = '$jz_date' and department = '$dpment' and jz_flag = 2") -> select();
        //根据jz_date查询病情（健康档案显示的内容）
        $bqres = $bq -> where("jz_date = '$jz_date'") -> select();
        $br_id = $result[0]['br_id'];
        $xh = $result[0]['xh'];
        //健康档案内容
        $jkres = $jkda -> where("br_id = '$br_id' and xh = '$xh'") -> select();
        //中药处方
        $zyres = $zy -> field('presc_no,presc_name,dose,order_text') -> where("patient_id = '$br_id' and xh = '$xh'") -> select();
        //中药处方明细
        for($i=0;$i<count($zyres);$i++){
            $jkres[1][$i]['presc_name'] = $zyres[$i]['presc_name'];
            $jkres[1][$i]['dose'] = $zyres[$i]['dose'];
            $jkres[1][$i]['order_text'] = $zyres[$i]['order_text'];
            $num = $zyres[$i]['presc_no'];
            $zydres = $zy_detail -> where("presc_no = '$num'") -> select();
            for($j=0;$j<count($zydres);$j++){
                $jkres[1][$i]['drug_name'][$j] = $zydres[$j]['drug_name'];
                $jkres[1][$i]['amount'][$j] = $zydres[$j]['amount'];
                $jkres[1][$i]['drug_units'][$j] = $zydres[$j]['drug_units'];
                $jkres[1][$i]['usage'][$j] = $zydres[$j]['usage'];
            }
        }
        //西药处方
        $xyres = $xy -> where("br_id = '$br_id' and xh = '$xh'") -> select();
        for($k=0;$k<count($xyres);$k++){
            $jkres[2][$k]['yp_name'] = $xyres[$k]['yp_name'];
            $jkres[2][$k]['yp_spec'] = trim($xyres[$k]['yp_spec']);
            $jkres[2][$k]['yp_yc_amount'] = $xyres[$k]['yp_yc_amount'];
            $jkres[2][$k]['yp_total_amount'] = floor($xyres[$k]['yp_total_amount']);
            $jkres[2][$k]['danwei'] = substr(trim($xyres[$k]['yp_spec']),-3);
            $jkres[2][$k]['yp_useage'] = $xyres[$k]['yp_useage'];
            $jkres[2][$k]['yp_pl_day'] = $xyres[$k]['yp_pl_day'];
            $jkres[2][$k]['fs'] = $xyres[$k]['fs'];
            $jkres[2][$k]['yp_mem'] = $xyres[$k]['yp_mem'];
        }
        $jkres[0]['br_id'] = $br_id;
        $jkres[0]['jz_date'] = $jz_date;
        $jkres[0]['br_name'] = $result[0]['br_name'];
        $jkres[0]['xb'] = $result[0]['xb'];
        $jkres[0]['nl'] = $result[0]['nl'];
        $jkres[0]['cs_date'] = $result[0]['cs_date'];
        $jkres[0]['pass'] = $result[0]['pass'];
        $jkres[0]['tel'] = $result[0]['tel'];
        $jkres[0]['dw'] = $result[0]['dw'];
        $jkres[0]['zyzd'] = $bqres[0]['zy_name'];
        $jkres[0]['xyzd'] = $bqres[0]['xy_name'];
        $jkres[0]['zybz'] = $bqres[0]['lunzhi'];
        $jkres[0]['zyzz'] = $bqres[0]['lunzhi_sm'];
        //dump($jkres);exit;
        $this->ajaxReturn($jkres,'json');
    }


    //中药诊治查询
    public function zyzzchaxun(){
        $this->display();
    }
    function zyselect(){//根据提交条件统计结果
        $sub=I('post.');
        //dump($sub);exit;
        $sql1="select count(distinct br_id) rs,count(distinct br_Id+cast(xh as varchar(10))) ls,case when isnull(diag_name,' 未定义') in('中医病名','') then ' 未定义' else  isnull(diag_name,' 未定义') end,datediff(year,cs_date,getdate()) nl,xb
                from(
                select c.br_id,c.xh,b.xb,b.cs_date,c.jz_date,d.diag_name
                from station_p b,bqxx c
                CROSS APPLY dbo.fnGetZYName(c.br_id,c.xh) d
                where b.br_id=c.br_id and b.xh=c.xh
                and b.jz_flag='2'
                and c.jz_date>='$sub[zyzz_start] 00:00:00.000'
                and c.jz_date<='$sub[zyzz_end] 23:59:59.000'
                )a
                group by case when isnull(diag_name,' 未定义') in('中医病名','') then ' 未定义' else  isnull(diag_name,' 未定义') end,datediff(year,cs_date,getdate()),xb;
            ";
        $sql2="select count(distinct br_id) rs,count(distinct br_Id+cast(xh as varchar(10))) ls,case when isnull(diag_name,' 未定义') in('中医病名','') then ' 未定义' else  isnull(diag_name,' 未定义') end,datediff(year,cs_date,getdate()) nl,xb
                from(
                select c.br_id,c.xh,b.xb,b.cs_date,c.jz_date,d.diag_name
                from station_p b,bqxx c
                CROSS APPLY dbo.fnGetZX(c.br_id,c.xh) d
                where b.br_id=c.br_id and b.xh=c.xh
                and b.jz_flag='2'
                and c.jz_date>='$sub[zyzz_start] 00:00:00.000'
                and c.jz_date<='$sub[zyzz_end] 23:59:59.000'
                )a
                group by case when isnull(diag_name,' 未定义') in('中医病名','') then ' 未定义' else  isnull(diag_name,' 未定义') end,datediff(year,cs_date,getdate()),xb;
            ";
        $sql3="select count(distinct br_id) rs,count(distinct br_Id+cast(xh as varchar(10))) ls,case when isnull(diag_name,' 未定义') in('中医病名','') then ' 未定义' else  isnull(diag_name,' 未定义') end,datediff(year,cs_date,getdate()) nl,xb
                from(
                select c.br_id,c.xh,b.xb,b.cs_date,c.jz_date,d.diag_name
                from station_p b,bqxx c
                CROSS APPLY dbo.fnGetZF(c.br_id,c.xh) d
                where b.br_id=c.br_id and b.xh=c.xh
                and b.jz_flag='2'
                and c.jz_date>='$sub[zyzz_start] 00:00:00.000'
                and c.jz_date<='$sub[zyzz_end] 23:59:59.000'
                )a
                group by case when isnull(diag_name,' 未定义') in('中医病名','') then ' 未定义' else  isnull(diag_name,' 未定义') end,datediff(year,cs_date,getdate()),xb;
            ";
        $result['bm']=M()->query($sql1);
        $result['zx']=M()->query($sql2);
        $result['zf']=M()->query($sql3);
        $result['nld']=$sub[zyzz_nld];
        $result['sex']=$sub[zyzz_sex];
        
        $this->ajaxReturn($result);
    }
    //西药诊治查询
    public function xyzzchaxun(){
        $this->display();
    }
    function xyselect(){
        $sub=I('post.');

        $sql="select count(distinct br_id) rs,
            count(distinct br_id+cast(xh as varchar(10))) ls,
            case when isnull(diag_name,'未定义') in('西医病名','') then '未定义' else  isnull(diag_name,'未定义') end,
            datediff(year,cs_date,getdate()) nl,xb
            from(
            select c.br_id,c.xh,b.xb,b.cs_date,c.jz_date,d.diag_name
            from station_p b,bqxx c
            CROSS APPLY dbo.fnGetXYName(c.br_id,c.xh) d
            where b.br_id=c.br_id and b.xh=c.xh
			and b.jz_flag='2'
            and c.jz_date>='$sub[zyzz_start] 00:00:00.000'
            and c.jz_date<='$sub[zyzz_end] 23:59:59.000') ss
            group by case when isnull(diag_name,'未定义') in('西医病名','') then '未定义' else  isnull(diag_name,'未定义') end,
            datediff(year,cs_date,getdate()) ,xb;";
        $result['res']=M()->query($sql);

        $result['nld']=$sub[zyzz_nld];
        $result['sex']=$sub[zyzz_sex];
        $this->ajaxReturn($result);
    }
}