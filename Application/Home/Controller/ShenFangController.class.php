<?php
namespace Home\Controller;

class ShenFangController extends PublicController{
    protected function __initialize(){
        parent::_initialize();
    }
    /***提交处方***/
	public function subsf(){
		$mod=M('dshdict');
        $condition['doctor_id']=session(wh_userId);
        $date1=date('Y-m-d H:i:s',strtotime("-7 day"));
        $date2=date("Y-m-d H:i:s");
        $condition['presc_date']=array('between',"$date1,$date2");
		$res=$mod->field('presc_no,presc_date,operator,xh,patient_id,indicate,doctor_id,rcpt_no,hzsrc,blsrc,cause,bhtoken,tj_date,chief_id,department')
		->where($condition)
        ->order('indicate,rcpt_no desc')->page($_GET['p'],8)->select();
		foreach($res as $k=>$v){
			$pat=M('station_p')->where("BR_ID='$v[patient_id]' and xh='$v[xh]' and department='$v[department]'")->find();
			$res[$k]['br_info']=$pat['br_name']."/".$pat['xb']."/".$pat['nl'];
			$res[$k]['blh']=$pat['blqsh'];
            $uname=M('user_info_dict')->field('userName')->where("id='$v[chief_id]'")->find();      
            $res[$k]['chiefname']=$uname['username'];
		}
        //所有上级部门人员
        $qufenshijian = session(wh_userId);
        $user=M('user_info_dict');
        $dpt=$user->field('b1.department,b2.pid')
                ->alias('b1')
                ->join('left join about as b2 on b1.department=b2.id')
                ->where("b1.id='$qufenshijian' and b1.department=b2.id")
                ->find();
        $chief=$user->field('id,userName,online')->where("department='$dpt[pid]'")->order('online desc')->select();
        $cons=$mod->where($condition)->count();
        $page = new \Think\Page($cons,8);

        $page->rollPage=5;
        $page->lastSuffix=false;

        $page->setConfig('next','下一页');
        $page->setConfig('prev','上一页');
        $page->setConfig('first','首页');
        $page->setConfig('last','末页');

        $show = $page->show();
        $this->assign('page',$show);
		$this->assign('res',$res);
		$this->assign('brcon',$pat);
        $this->assign('chief',$chief);
	    $this->display();
	}
	function hzupload(){//上传患者信息
		$name=$_POST['presc_no'];
		$mod=M('dshdict');
        $res=$mod->field('hzurl,hzsrc')->where("presc_no='$name'")->find();
        if(!empty($res['hzurl']) || !empty($res['hzsrc'])){
            unlink(str_replace('/zySystem/Public','Public',$res['hzurl']));
            unlink(str_replace('/zySystem/Public','Public',$res['hzurl']));
            $empty['hzurl']='';
            $empty['hzsrc']='';
            $mod->where("presc_no='$name'")->save($empty);
        }
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;
		$upload->exts = array('jpg','gif','png','jpeg');   
		$upload->rootPath = './Public/hzload/';
    	$upload->autoSub = false;
    	$upload->savePath = '';
    	$upload->saveName = $name;
		$info = $upload->upload();
		if(info){
			$allname=$info['hzpic']['savename'];
			$path="./Public/hzload/".$allname;
			$image = new \Think\Image(); 
			$image->open($path);
			$src="./Public/hzslt/".$allname;
			$image->thumb(150, 30)->save($src);
			$data[hzurl]=str_replace('./Public','/zySystem/Public',$path);
			$data[hzsrc]=str_replace('./Public','/zySystem/Public',$src);
			$mod->where("presc_no='$_POST[presc_no]'")->save($data);
			$this->redirect('ShenFang/subsf',0);
		}else{
			$this->error($upload->getError());
		}   
	}
	function blupload(){//上传病历信息
		$name=$_POST['presc_no'];
		$mod=M('dshdict');
        $res=$mod->field('blurl,blsrc')->where("presc_no='$name'")->find();
        if(!empty($res['blurl']) || !empty($res['blsrc'])){
            unlink(str_replace('/zySystem/Public','Public',$res['blurl']));
            unlink(str_replace('/zySystem/Public','Public',$res['blsrc']));
            $empty['blurl']='';
            $empty['blsrc']='';
            $mod->where("presc_no='$name'")->save($empty);
        }
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;
		$upload->exts = array('jpg','gif','png','jpeg');   
		$upload->rootPath = './Public/blload/';
		$upload->autoSub = false;
    	$upload->savePath = '';
    	$upload->saveName = $name;
		$info = $upload->upload();
		if(info){
			$allname=$info['blpic']['savename'];
			$path="./Public/blload/".$allname;
			$image = new \Think\Image(); 
			$image->open($path);
			$src="./Public/blslt/".$allname;
			$image->thumb(150, 30)->save($src);
			$data[blurl]=str_replace('./Public','/zySystem/Public',$path);
			$data[blsrc]=str_replace('./Public','/zySystem/Public',$src);
			$mod->where("presc_no='$_POST[presc_no]'")->save($data);
			$this->redirect('ShenFang/subsf',0);
		}else{
			$this->error($upload->getError());
		}
	}
	function subcheck(){//提交审核
        $code = $_GET['presc_no'];
      $dict = M('prescription');
      $data['indicate'] = 100;
      $where['presc_no'] = $code;
      $res = $dict->where($where)->save($data);
		$mod=M('dshdict');
		$data[indicate]='1';
		$data[tj_date]=date('Y-m-d H:i:s');
        $data[chief_id]=$_GET['chief'];
		$info=$mod->where("presc_no='$_GET[presc_no]'")->save($data);
		if($info){
			$this->success('ShenFang/subsf',0);
		}else{
			$this->error('提交失败！',2);
		}
	}
	function hzinfo(){//获取患者信息
		$mod=M('station_p');
		$res=$mod
        ->where("BR_ID='$_POST[patient_id]' and xh='$_POST[xh]' and department='$_POST[dpment]'")->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,tel,br_id,xh,dw,pass,e_mail,ghf,fax,convert(varchar(10),cs_date,120) as cs_date')->find();
		$this->ajaxReturn($res);
	}
    function topinfo(){//改变top信息条
        $result=M('station_p')
        ->field('convert(varchar(19),jz_date,120) as jz_date,br_name,xb,nl,xh,blqsh')
        ->where("br_id='$_POST[patient_id]' and xh='$_POST[xh]' and department='$_POST[dpment]'")
        ->find();
        $this->ajaxReturn($result);
    }
    //获取病历信息
	function blinfo(){
		$cf_id = $_POST['cf_id'];
		$jbxx = M('station_p');//病人基本信息表
        $jkda = M('jkda_xx');//健康档案信息表
        $bq = M('bqxx');//病情信息表
        $zy = M('prescription');//中药处方表
        $zy_detail = M('prescription_detail');//中药明细表
        $xy = M('xydrugcf_detial');//西药明细表
		$chfres = $zy -> field('patient_id,xh') -> where("presc_no = '$cf_id'") -> select();
		$br_id = $chfres[0]['patient_id'];
        $xh = $chfres[0]['xh'];
		$result = $jbxx -> where("br_id = '$br_id' and xh = '$xh'") ->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,tel,br_id,xh,dw,pass,e_mail,ghf,fax,convert(varchar(10),cs_date,120) as cs_date,convert(varchar(10),jz_date,120) as jz_date')-> select();
		$jz_date = $result[0]['jz_date'];
        $jkres1 = $jkda -> where("br_id = '$br_id' and xh = '$xh'") -> select();
        $jkres2 = $bq -> where("br_id = '$br_id' and xh = '$xh'") -> select();
        //中药处方
        $zyres = $zy -> field('presc_no,presc_name,dose,order_text') -> where("patient_id = '$br_id' and xh = '$xh' and (indicate = '1' or indicate = '2')") -> select();
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
        $xyres = $xy -> distinct(true) -> field('cf_id') -> where("br_id = '$br_id' and xh = '$xh' and (cf_flag = '1' or cf_flag = '2')") -> select();
        $m = 0;
        for($k=0;$k<count($xyres);$k++){
        	$cf_id = $xyres[$k]['cf_id'];
        	$xyjg = $xy -> where("cf_id = '$cf_id'") -> select();
        	for($l=0;$l<count($xyjg);$l++){
        		$jkres[2][$m][$l]['yp_name'] = $xyjg[$l]['yp_name'];
	            $jkres[2][$m][$l]['yp_spec'] = trim($xyjg[$l]['yp_spec']);
	            $jkres[2][$m][$l]['yp_yc_amount'] = $xyjg[$l]['yp_yc_amount'];
	            $jkres[2][$m][$l]['yp_total_amount'] = floor($xyjg[$l]['yp_total_amount']);
	            $jkres[2][$m][$l]['danwei'] = substr(trim($xyjg[$l]['yp_spec']),-3);
	            $jkres[2][$m][$l]['yp_useage'] = $xyjg[$l]['yp_useage'];
	            $jkres[2][$m][$l]['yp_pl_day'] = $xyjg[$l]['yp_pl_day'];
	            $jkres[2][$m][$l]['fs'] = $xyjg[$l]['fs'];
	            $jkres[2][$m][$l]['yp_mem'] = $xyjg[$l]['yp_mem'];
        	}
        	$m++;
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
        $jkres[0]['zs'] = $jkres1[0]['zs'];
        $jkres[0]['jws'] = $jkres1[0]['jws'];
        $jkres[0]['jws_date'] = $jkres1[0]['jws_date'];
        $jkres[0]['crbs'] = $jkres1[0]['crbs'];
        $jkres[0]['crbs_date'] = $jkres1[0]['crbs_date'];
        $jkres[0]['jts_fq1'] = $jkres1[0]['jts_fq1'];
        $jkres[0]['jts_fq2'] = $jkres1[0]['jts_fq2'];
        $jkres[0]['jts_mq1'] = $jkres1[0]['jts_mq1'];
        $jkres[0]['jts_mq2'] = $jkres1[0]['jts_mq2'];
        $jkres[0]['jts_xdjm1'] = $jkres1[0]['jts_xdjm1'];
        $jkres[0]['jts_xdjm2'] = $jkres1[0]['jts_xdjm2'];
        $jkres[0]['jts_zn1'] = $jkres1[0]['jts_zn1'];
        $jkres[0]['jts_zn2'] = $jkres1[0]['jts_zn2'];
        $jkres[0]['gms'] = $jkres1[0]['gms'];
        $jkres[0]['weight'] = $jkres1[0]['weight'];
        $jkres[0]['temperature'] = $jkres1[0]['temperature'];
        $jkres[0]['pulse'] = $jkres1[0]['pulse'];
        $jkres[0]['breath'] = $jkres1[0]['breath'];
        $jkres[0]['blood_pre1'] = $jkres1[0]['blood_pre1'];
        $jkres[0]['blood_pre2'] = $jkres1[0]['blood_pre2'];
        $jkres[0]['zt_wshen'] = $jkres1[0]['zt_wshen'];
        $jkres[0]['zt_wse'] = $jkres1[0]['zt_wse'];
        $jkres[0]['zt_tt'] = $jkres1[0]['zt_tt'];
        $jkres[0]['zt_tx'] = $jkres1[0]['zt_tx'];
        $jkres[0]['xz_smzl'] = $jkres1[0]['xz_smzl'];
        $jkres[0]['xz_smsj'] = $jkres1[0]['xz_smsj'];
        $jkres[0]['xz_sy'] = $jkres1[0]['xz_sy'];
        $jkres[0]['xz_kw'] = $jkres1[0]['xz_kw'];
        $jkres[0]['xz_dbbc'] = $jkres1[0]['xz_dbbc'];
        $jkres[0]['xz_dbbz'] = $jkres1[0]['xz_dbbz'];
        $jkres[0]['xz_xbbc'] = $jkres1[0]['xz_xbbc'];
        $jkres[0]['xz_xbbs'] = $jkres1[0]['xz_xbbs'];
        $jkres[0]['qz_xq'] = $jkres1[0]['qz_xq'];
        $jkres[0]['qz_xg'] = $jkres1[0]['qz_xg'];
        $jkres[0]['xj'] = $jkres1[0]['xj'];
        $jkres[0]['sz_ss'] = $jkres1[0]['sz_ss'];
        $jkres[0]['sz_st'] = $jkres1[0]['sz_st'];
        $jkres[0]['sz_dt'] = $jkres1[0]['sz_dt'];
        $jkres[0]['sz_tz'] = $jkres1[0]['sz_tz'];
        $jkres[0]['sz_ts'] = $jkres1[0]['sz_ts'];
        $jkres[0]['mz'] = $jkres1[0]['mz'];
        $jkres[0]['tzzd'] = $jkres1[0]['tzzd'];
        $jkres[0]['zyzd'] = $jkres2[0]['zy_name'];
        $jkres[0]['xyzd'] = $jkres2[0]['xy_name'];
        $jkres[0]['zybz'] = $jkres2[0]['lunzhi'];
        $jkres[0]['zyzz'] = $jkres2[0]['lunzhi_sm'];
        $this->ajaxReturn($jkres,'json');
	}
	function zxsf(){
		$br_id = $_POST['br_id'];
		$xh = $_POST['xh'];
		$jbxx = M('station_p');//病人基本信息表
        $jkda = M('jkda_xx');//健康档案信息表
        $bq = M('bqxx');//病情信息表
        $zy = M('prescription');//中药处方表
        $zy_detail = M('prescription_detail');//中药明细表
        $xy = M('xydrugcf_detial');//西药明细表
		$result = $jbxx -> where("br_id = '$br_id' and xh = '$xh'") ->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,tel,br_id,xh,dw,pass,e_mail,ghf,fax,convert(varchar(10),cs_date,120) as cs_date,convert(varchar(10),jz_date,120) as jz_date') -> select();
		$jz_date = $result[0]['jz_date'];
        $jkres1 = $jkda -> where("br_id = '$br_id' and xh = '$xh'") -> select();
        $jkres2 = $bq -> where("br_id = '$br_id' and xh = '$xh'") -> select();
        //中药处方
        $zyres = $zy -> field('presc_no,presc_name,dose,order_text') -> where("patient_id = '$br_id' and xh = '$xh' and (indicate = '1' or indicate = '2')") -> select();
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
        $xyres = $xy -> distinct(true) -> field('cf_id') -> where("br_id = '$br_id' and xh = '$xh' and (cf_flag = '1' or cf_flag = '2')") -> select();
        $m = 0;
        for($k=0;$k<count($xyres);$k++){
        	$cf_id = $xyres[$k]['cf_id'];
        	$xyjg = $xy -> where("cf_id = '$cf_id'") -> select();
        	for($l=0;$l<count($xyjg);$l++){
        		$jkres[2][$m][$l]['yp_name'] = $xyjg[$l]['yp_name'];
	            $jkres[2][$m][$l]['yp_spec'] = trim($xyjg[$l]['yp_spec']);
	            $jkres[2][$m][$l]['yp_yc_amount'] = $xyjg[$l]['yp_yc_amount'];
	            $jkres[2][$m][$l]['yp_total_amount'] = floor($xyjg[$l]['yp_total_amount']);
	            $jkres[2][$m][$l]['danwei'] = substr(trim($xyjg[$l]['yp_spec']),-3);
	            $jkres[2][$m][$l]['yp_useage'] = $xyjg[$l]['yp_useage'];
	            $jkres[2][$m][$l]['yp_pl_day'] = $xyjg[$l]['yp_pl_day'];
	            $jkres[2][$m][$l]['fs'] = $xyjg[$l]['fs'];
	            $jkres[2][$m][$l]['yp_mem'] = $xyjg[$l]['yp_mem'];
        	}
        	$m++;
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
        $jkres[0]['zs'] = $jkres1[0]['zs'];
        $jkres[0]['jws'] = $jkres1[0]['jws'];
        $jkres[0]['jws_date'] = $jkres1[0]['jws_date'];
        $jkres[0]['crbs'] = $jkres1[0]['crbs'];
        $jkres[0]['crbs_date'] = $jkres1[0]['crbs_date'];
        $jkres[0]['jts_fq1'] = $jkres1[0]['jts_fq1'];
        $jkres[0]['jts_fq2'] = $jkres1[0]['jts_fq2'];
        $jkres[0]['jts_mq1'] = $jkres1[0]['jts_mq1'];
        $jkres[0]['jts_mq2'] = $jkres1[0]['jts_mq2'];
        $jkres[0]['jts_xdjm1'] = $jkres1[0]['jts_xdjm1'];
        $jkres[0]['jts_xdjm2'] = $jkres1[0]['jts_xdjm2'];
        $jkres[0]['jts_zn1'] = $jkres1[0]['jts_zn1'];
        $jkres[0]['jts_zn2'] = $jkres1[0]['jts_zn2'];
        $jkres[0]['gms'] = $jkres1[0]['gms'];
        $jkres[0]['weight'] = $jkres1[0]['weight'];
        $jkres[0]['temperature'] = $jkres1[0]['temperature'];
        $jkres[0]['pulse'] = $jkres1[0]['pulse'];
        $jkres[0]['breath'] = $jkres1[0]['breath'];
        $jkres[0]['blood_pre1'] = $jkres1[0]['blood_pre1'];
        $jkres[0]['blood_pre2'] = $jkres1[0]['blood_pre2'];
        $jkres[0]['zt_wshen'] = $jkres1[0]['zt_wshen'];
        $jkres[0]['zt_wse'] = $jkres1[0]['zt_wse'];
        $jkres[0]['zt_tt'] = $jkres1[0]['zt_tt'];
        $jkres[0]['zt_tx'] = $jkres1[0]['zt_tx'];
        $jkres[0]['xz_smzl'] = $jkres1[0]['xz_smzl'];
        $jkres[0]['xz_smsj'] = $jkres1[0]['xz_smsj'];
        $jkres[0]['xz_sy'] = $jkres1[0]['xz_sy'];
        $jkres[0]['xz_kw'] = $jkres1[0]['xz_kw'];
        $jkres[0]['xz_dbbc'] = $jkres1[0]['xz_dbbc'];
        $jkres[0]['xz_dbbz'] = $jkres1[0]['xz_dbbz'];
        $jkres[0]['xz_xbbc'] = $jkres1[0]['xz_xbbc'];
        $jkres[0]['xz_xbbs'] = $jkres1[0]['xz_xbbs'];
        $jkres[0]['qz_xq'] = $jkres1[0]['qz_xq'];
        $jkres[0]['qz_xg'] = $jkres1[0]['qz_xg'];
        $jkres[0]['xj'] = $jkres1[0]['xj'];
        $jkres[0]['sz_ss'] = $jkres1[0]['sz_ss'];
        $jkres[0]['sz_st'] = $jkres1[0]['sz_st'];
        $jkres[0]['sz_dt'] = $jkres1[0]['sz_dt'];
        $jkres[0]['sz_tz'] = $jkres1[0]['sz_tz'];
        $jkres[0]['sz_ts'] = $jkres1[0]['sz_ts'];
        $jkres[0]['mz'] = $jkres1[0]['mz'];
        $jkres[0]['tzzd'] = $jkres1[0]['tzzd'];
        $jkres[0]['zyzd'] = $jkres2[0]['zy_name'];
        $jkres[0]['xyzd'] = $jkres2[0]['xy_name'];
        $jkres[0]['zybz'] = $jkres2[0]['lunzhi'];
        $jkres[0]['zyzz'] = $jkres2[0]['lunzhi_sm'];
        $this->ajaxReturn($jkres,'json');
	}
	public function cfdetail(){//获取处方明细
		$sql="select * from dshdict_Detail where presc_no='".$_POST['presc_no']."' order by drug_no";
		$result=M('dshdict_Detail')->query($sql);
		$this->ajaxReturn($result);
	}
    /*
     * @杨旭亚
     * 2017-06-01
     * 获取处方用法 次数等
    **/
    public function huoquchufangyongfa(){//获取处方用法
        $unm = I('post.num');
        $where['presc_no'] =$unm;
        $user = M(prescription)->where($where)->field('usage1,decoction,dosage')->select();
        $this->ajaxReturn($user);
    }
	function imginfo(){
		$model=M('dshdict');
		$result=$model->field('presc_no,blurl,hzurl')->where("presc_no='$_POST[presc_no]'")->find();
		$this->ajaxReturn($result);
	}
	function bhxx(){
		$model=M('dshdict');
		$result=$model->field('cause')->where("presc_no='$_POST[presc_no]'")->find();
		$this->ajaxReturn($result);
	}
    /***提交处方结束***/
	/*****处方审核*****/
	function checksf(){
		$mod=M('dshdict');
        $model=M('station_p');
        $injz=$model->field('br_id')->where("jz_flag='1'")->select();
        $injz=array_column($injz,'br_id');//未完成就诊的病人ID
        $condition['indicate']='1';
        $seid = session(wh_userId);
        $condition['chief_id']="$seid";
        $condition['patient_id']=array('in',$injz);
		$res=$mod->field('presc_no,operator,patient_id,xh,indicate,doctor_id,hzsrc,rcpt_no,blsrc,hzurl,blurl,tj_date,chief_id,department')
		->where($condition)
       ->order('indicate,rcpt_no desc')->page($_GET['p'],8)->select();
		foreach($res as $k=>$v){
			$pat=$model->where("BR_ID='$v[patient_id]' and xh='$v[xh]' and department='$v[department]'")->find();
			$res[$k]['br_info']=$pat['br_name']."--".$pat['xb']."--".$pat['nl'];
			$res[$k]['blh']=$pat['blqsh'];
			$hos=M()->field('t1.id as id1,t1.department,t2.id as id2,t2.hospital')
					->table('user_info_dict as t1,about as t2')
					->where("t1.id='$v[doctor_id]' and t1.department=t2.id")
					->find();
			$res[$k]['hospital']=$hos['hospital'];
			$brinfo[]=$pat;
		}
        $cons=$mod->where("indicate='1' and chief_id='$seid'")->count();
        $page = new \Think\Page($cons,8);

        $page->rollPage=5;
        $page->lastSuffix=false;

        $page->setConfig('next','下一页');
        $page->setConfig('prev','上一页');
        $page->setConfig('first','首页');
        $page->setConfig('last','末页');

        $show = $page->show();
        $this->assign('page',$show);
		$this->assign('res',$res);
		$this->assign('brcon',$pat);
		
		$this->display();
	}
	function bh(){//上级审核--驳回处方
		$mod=M('dshdict');
		$con=I('post.');
		$data['cause']=$con['cause'];
		$data['indicate']='0';
		$data['bhtoken']='1';
		$mod->where("presc_no='$con[presc_no]'")->save($data);
        $flag['indicate']='4';
        M('prescription')->where("presc_no='$con[presc_no]'")->save($flag);
		$this->redirect('ShenFang/checksf',0);
	}
	function endcheck(){//上级审核
        $mod=M('dshdict');
        $model=M('dshdict_detail');
        $yftoken=M('dshdict')->field('pregnant_woman,department as dpt')->where("presc_no='$_GET[presc_no]'")->find();
        //判断是否是孕妇并审核孕妇禁药
        if($yftoken[pregnant_woman]=='1'){
            $result=M()->field('b1.drug_no,b1.drug_name,b2.pregnant_women')
            ->table('dshdict_Detail as b1,dict_drug_zy as b2')
            ->where("b1.drug_code=b2.drug_code and b1.presc_no='$_GET[presc_no]' and b2.department='$yftoken[dpt]'")
            ->select();
            foreach($result as $v){
                if($v[pregnant_women]=='1'){
                    $str1.="&emsp;&emsp;第".$v[drug_no]."味药【".$v[drug_name]."】为孕妇慎用药品!".'<br>';
                }else if($v[pregnant_women]=='2'){
                    $str2.="&emsp;&emsp;第".$v[drug_no]."味药【".$v[drug_name]."】为孕妇禁用药品!".'<br>';
                }
            }
        }
        //18反19畏
        $ypcode=$model->field('drug_code')->where("presc_no='$_GET[presc_no]'")->select();
        foreach($ypcode as $k=>$v){
            array_shift($ypcode);
            foreach($ypcode as $key=>$value){
                $data=M('jy')
                ->where("(YP1='$value[drug_code]' and YP2='$v[drug_code]') or (YP1='$v[drug_code]' and YP2='$value[drug_code]')")
                ->select();
                if(count($data)>0){
                    $res[]=$data[0];
                }
            }
        }
        foreach($res as $k=>$v){
            $name1=$model->field('drug_no,drug_name')->where("presc_no='$_GET[presc_no]' and drug_code='$v[yp1]'")->find();
            $name2=$model->field('drug_no,drug_name')->where("presc_no='$_GET[presc_no]' and drug_code='$v[yp2]'")->find();
            if($v[lx]==1){ 
                $str3.="&emsp;&emsp;第".$name1[drug_no]."味药【".$name1[drug_name]."】和第".$name2[drug_no]."味药【".$name2[drug_name]."】是反药！".'<br>'; 
            }else if($v[lx]==2){
                $str4.="&emsp;&emsp;第".$name1[drug_no]."味药【".$name1[drug_name]."】和第".$name2[drug_no]."味药【".$name2[drug_name]."】是畏药！".'<br>'; 
            }
        }
        //药品用量超标
        $ypcode=$model->field('drug_code')->where("presc_no='$_GET[presc_no]'")->select();
        $ypamount=$model->field('amount')->where("presc_no='$_GET[presc_no]'")->select();
        $ypcode=i_array_column($ypcode,'drug_code');
        $jbxx=M('jbxx');
        $allcl=$jbxx->where("xxlbdm='03'")->select();
        $xxdm=i_array_column($allcl,'xxdm');
        $itst=array_intersect($ypcode,$xxdm);
        foreach($itst as $k=>$v){
            $res1=$model->where("presc_no='$_GET[presc_no]' and drug_code='$v'")->find();
            $res2=$jbxx->where("xxlbdm='03' and xxdm='$v'")->find();
            if((int)$res1[amount]>(int)$res2[bz]){
                $str5.="&emsp;&emsp;第".$res1[drug_no]."味药【".$res1[drug_name]."】用量超过使用范围（".$res2[bz]."克）!<br/>";
            }
        }
        //有毒药品
        $dres=M()->field('b1.dx,b2.drug_no,b2.drug_name,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(b1.zysx)) as zysx')
        ->table('dict_drug_zy_mx as b1,dshdict_Detail as b2')
        ->where("b1.drug_code=b2.drug_code and b2.presc_no='$_GET[presc_no]' and b1.department='$yftoken[dpt]'")
        ->select();
        foreach($dres as $k=>$v){
            if($v[dx]=='1'){
                $stab[$k]['drug_name']=$v[drug_name];
                $stab[$k]['dx']="有毒";
                $stab[$k]['zysx']=$v[zysx];
            }else if($v[dx]=='2'){
                $stab[$k]['drug_name']=$v[drug_name];
                $stab[$k]['dx']="小毒";
                $stab[$k]['zysx']=$v[zysx];
            }else if($v[dx]=='3'){
                $stab[$k]['drug_name']=$v[drug_name];
                $stab[$k]['dx']="大毒";
                $stab[$k]['zysx']=$v[zysx];
            }else if($v[dx]=='4'){
                $stab[$k]['drug_name']=$v[drug_name];
                $stab[$k]['dx']="剧毒";
                $stab[$k]['zysx']=$v[zysx];
            }
        }
        $token='';
        if(empty($str1) && empty($str2) && empty($str3) && empty($str4) && empty($str5) && empty($stab)){
            $token='1';
            $sql="select * from dshdict_Detail where presc_no='".$_GET['presc_no']."' order by drug_no";
            $cfinfo=$model->query($sql);
            $this->assign('cfinfo',$cfinfo);
            $this->assign('token',$token);
            $this->assign('id',$_GET['presc_no']);
            $this->display();
        }else{
            $this->assign('str1',$str1);
            $this->assign('str2',$str2);
            $this->assign('str3',$str3);
            $this->assign('str4',$str4);
            $this->assign('str5',$str5);
            $this->assign('stab',$stab);
            $this->assign('token',$token);
            $this->assign('id',$_GET['presc_no']);
            $this->display();
        }
	}
    function pass(){
        //dump($_GET[presc_no]);exit;
        $mod=M('dshdict');
        $data[indicate]='2';
        $info=$mod->where("presc_no='$_GET[presc_no]'")->save($data);
        $flag[indicate]='1';//改自定义状态（改成审核状态）
        $infop=M('Prescription')->where("presc_no='$_GET[presc_no]'")->save($flag);
        if($info){
            $this->success('审核通过！',U('ShenFang/checksf'),2);
        }else{
            $this->error('审核失败！',U('ShenFang/checksf'),2);
        }
    }
}