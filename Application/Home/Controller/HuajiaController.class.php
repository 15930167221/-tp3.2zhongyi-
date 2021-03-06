<?php
namespace Home\Controller;

class HuajiaController extends PublicController {

    protected function __initialize()
    {
        parent::_initialize();
    }
    public function huajia(){

        //病人信息
        $br_id = session('id');
        $xh = session('xh');
        $department = session('dpment');
        $jbxx = M('station_p');//病人基本信息
        $data = $jbxx -> where("br_id = '$br_id' and xh = '$xh' and department = '$department'") -> select();

        //收费列表信息(审核处方之后)
        if($_POST){
            if($_GET['flag'] == 1){
                $flag = 1;//收费
            }else{
                $flag = 2;
            }
            $shouf = M('g_outp_bill_item');

            $len = count($_POST['xuhao']);
//           dump(I('post.'));die();
            /**
             * yxy
             * @date 2017-05-23
             */
            $zongsz = I('post.'); 
//             dump($zongsz);die;
            //获取公共数据
            $sf_pjh = $zongsz[sf_pjh];
            $name = $zongsz[name];
            $guige = $zongsz[guige];
            //遍历子数组
            $bill = $zongsz[bill];
            foreach ($bill as $k => $v) {
                $arr[$k][BILL_CODE] = $v;
                $arr[$k][SERIAL_NO] = $zongsz[xuhao][$k];
                //获取 item_code 
                $it_code = M('p_price_list');//查询项目代码
                $name = $zongsz[xmname][$k];
                $code = $it_code -> field('ITEM_CODE') -> where("item_name = '$name' and department = '$department'") -> select();
                $arr[$k][ITEM_CODE] = $code[0][item_code];//项目代码
                $arr[$k][CLINIC_NUM] = $br_id;//病人ID
                $arr[$k][xh] = $data[0]['xh'];//挂号序号
                $date = date('Y-m-d H:i:s');
                $arr[$k][CHARGE_DATE] = $date;//收费日期
                $arr[$k][UNIT_PRICE] = $zongsz[danjia][$k];//单价
                $arr[$k][AMOUNT] = $zongsz[number][$k];//数量
                $arr[$k][UNITS] = $zongsz[danwei][$k];//单位
                $arr[$k][TOTAL] = $zongsz[jine][$k];//金额
                $arr[$k][OPERATOR_CODE] = $_SESSION['wh_userId'];//操作员编码
                $arr[$k][BILL_STATUS] = $flag;//收费状态
                $arr[$k][RETURN_DATE] = '';//退费日期
                $arr[$k][INVOICE_NO] = $sf_pjh;//发票号
                $arr[$k][DEPARTMENT] = $department;
                $arr[$k][qufenleibie] = $zongsz[qufenzhongyao][$k];//区分中药
                $arr[$k][UNIT_PRICE] = str_replace(',','',$arr[$k][UNIT_PRICE]);
                $arr[$k][TOTAL] = str_replace(',', '', $arr[$k][TOTAL]);

                $res = $shouf -> add($arr[$k]);
                if($res){
                    //收费成功后需将收费标识(indicate和cf_flag)更改为2(0是未审核，1是已审核，2是已收费)
                    $presc_no = $arr[$k][BILL_CODE];
                    if($arr[$k][ITEM_CODE] == '03'){
                        $zcy = M('prescription');
                        $zcy -> where("presc_no = '$presc_no' and patient_id = '$br_id' and xh = '$xh' and department = '$department'") -> setField(array('indicate'=>'2','rcpt_no'=>$_POST['sf_pjh']));
                    }
                    if($arr[$k][ITEM_CODE] == '01'){
                        $xy = M('xydrugcf_detial');
                        $xy -> where("cf_id = '$presc_no' and br_id = '$br_id' and xh = '$xh' and department = '$department'") -> setField(array('cf_flag'=>'2','invoice_no'=>$_POST['sf_pjh']));
                    }
                    if($arr[$k][ITEM_CODE] == '02'){
                        $zchy = M('xydrugcf_detial');
                        $zchy -> where("cf_id = '$presc_no' and br_id = '$br_id' and xh = '$xh' and department = '$department'") -> setField(array('cf_flag'=>'2','invoice_no'=>$_POST['sf_pjh']));
                    }
                }
            }
            if($res){
                $this -> success('病人收费成功！',U('Huajia/huajia',array("id" => $br_id,"xh" => $xh)),1);
            }else{
                $this -> error('系统异常，收费失败！',U('Huajia/huajia',array("id" => $br_id,"xh" => $xh)),2);
            }
		}else{
            if($_GET){
                $department = session('dpment');
                $jbxx = M('station_p');//病人基本信息
                $data = $jbxx -> where("br_id = '$br_id' and xh = '$xh' and department = '$department'") -> select();
                $shoufeib = M('g_outp_bill_item');//收费信息 票据号
                //生成票据号
                $pjhao = $shoufeib  -> field('invoice_no') -> select();
                $pjhao_l = count($pjhao);
                $pjharr = array();
                for($i=0;$i<$pjhao_l;$i++){
                    if(preg_match("/^[0-9]/",$pjhao[$i]['invoice_no'])){
                        $pjharr[$i] = $pjhao[$i]['invoice_no']; 
                    }
                }
                $maxpjh = max($pjharr);
                $strpjh = substr($maxpjh,0,8);
                $date = date('Ymd');
                if($strpjh == $date){
                    $maxpjh = $maxpjh + 1;
                }else{
                    $maxpjh = $date.'00001';
                }

                //查看已开处方
                $zy = M('prescription');//历史处方表
                $zy_detail = M('prescription_detail');//处方明细表
                $xy = M('xydrugcf_detial');//西药处方表
                $xy_detail = M('drug_dict');//西药药品明细表
                //查看当前病人信息(indicate为空表示未审核，为1表示已审核，为2表示已收费)
                $zykf = $zy -> where("patient_id = '$br_id' and xh = '$xh' and indicate = 1 and department = '$department'")->order('presc_no asc') -> select();
                // dump($zykf);die;
                $presc_no =  $zykf[0][presc_no];//查询该病人未收费的项目
                $zykf1 = $zy_detail -> where("presc_no = '$presc_no' and department = '$department'") -> select();
                $zyyp = array();
                $zylen = count($zykf1);
                //循环生成页面数据：单位、单价、数量、金额
                for($i=0;$i<$zylen;$i++){
                    $zyyp['amount'] += $zykf1[$i][amount];
                    $zyyp['drug_units'] = '克';
                    $zyyp['price'] += $zykf1[$i][costs];
                    $zyyp['costs'] += $zykf1[$i][costs];
                }
                $zyyp['costs'] = $zyyp['costs'] * $zykf[0][dose];
                //小数位
                $zyyp['amount'] = number_format($zyyp['amount'],2);
                $zyyp['price'] = number_format($zyyp['price'],2);
                $zyyp['costs'] = number_format($zyyp['costs'],2);

                //西药处方
                //查看当前病人信息
                //(cf_flag为空表示未审核，为1表示已审核，为2表示已收费)
                $xykf = $xy -> field("cf_id") -> where("br_id = '$br_id' and xh = '$xh' and cf_flag = 1 and department = '$department'") -> group("cf_id") -> select();//查找开了几个处方

                $this -> assign('zyyp',$zyyp);
                $this -> assign('data',$data);
                //$this -> assign('xydrug',$xydrug);
                $this -> assign('czjine',$czjine);         
                $this -> assign('pjh',$maxpjh);
                $this -> assign('zykf',$zykf);
                $this -> assign('xykf',$xykf);
                $this -> assign('br_id',$br_id);
                $this -> assign('xh',$xh);
                $this->display();
            }else{
                $department = session('dpment');
                $jbxx = M('station_p');//病人基本信息
                $data = $jbxx -> where("br_id = '$br_id' and xh = '$xh' and department = '$department'") -> select();
                $shoufeib = M('g_outp_bill_item');//收费信息 票据号
                //生成票据号
                $pjhao = $shoufeib  -> field('invoice_no') -> select();
                $pjhao_l = count($pjhao);
                $pjharr = array();
                for($i=0;$i<$pjhao_l;$i++){
                    if(preg_match("/^[0-9]/",$pjhao[$i]['invoice_no'])){
                        $pjharr[$i] = $pjhao[$i]['invoice_no']; 
                    }
                }
                $maxpjh = max($pjharr);
                $strpjh = substr($maxpjh,0,8);
                $date = date('Ymd');
                if($strpjh == $date){
                    $maxpjh = $maxpjh + 1;
                }else{
                    $maxpjh = $date.'00001';
                }

                //查看已开处方
                $zy = M('prescription');//历史处方表
                $zy_detail = M('prescription_detail');//处方明细表
                $xy = M('xydrugcf_detial');//西药处方表
                $xy_detail = M('drug_dict');//西药药品明细表
                //查看当前病人信息(indicate为空表示未审核，为1表示已审核，为2表示已收费)
                //中药处方
                $zykf = $zy -> where("patient_id = '$br_id' and xh = '$xh' and indicate = 1 and department = '$department'") -> select();
                $sum = count($zykf);
                $zyyp = array();
                for ($j = 0; $j < $sum; $j++) {
                    // dump($j);die;
                    $presc_no =  $zykf[$j][presc_no];//查询该病人未收费的项目
                    $dose =  $zykf[$j]['dose'];//查询该病人的位数
                    // dump($presc_no);
                    $zykf1 = $zy_detail -> where("presc_no = '$presc_no' and department = '$department'") -> select();
                    $zylen = count($zykf1);
                    //循环生成页面数据：单位、单价、数量、金额
                    for($i=0;$i<$zylen;$i++){
                        $zyyp[$j]['amount'] += $zykf1[$i][amount];
                        $zyyp[$j]['drug_units'] = '克';
                        $zyyp[$j]['price'] += $zykf1[$i][costs] * $dose;
                        $zyyp[$j]['costs'] += $zykf1[$i][costs] * $dose;
                        // dump($zyyp[$j]['costs']);
                    }
                    //小数位
                    $zyyp[$j]['amount'] = number_format($zyyp[$j]['amount'],2);
                    $zyyp[$j]['price'] = number_format($zyyp[$j]['price'],2);
                    $zyyp[$j]['costs'] = number_format($zyyp[$j]['costs'],2);
                }
                // die;
                //西药处方
                //查看当前病人信息
                //(cf_flag为空表示未审核，为1表示已审核，为2表示已收费)
                $xykf = $xy -> field("cf_id") -> where("br_id = '$br_id' and xh = '$xh' and cf_flag = 1 and department = '$department'") -> group('cf_id') -> select();//查找开了几个处方  
                //dump($xykf);
                $this -> assign('zyyp',$zyyp);
                $this -> assign('data',$data);
                //$this -> assign('xydrug',$xydrug);
                $this -> assign('czjine',$czjine);         
                $this -> assign('pjh',$maxpjh);
                $this -> assign('zykf',$zykf);
                $this -> assign('xykf',$xykf);
                $this -> assign('br_id',$br_id);
                $this -> assign('xh',$xh);
                $this->display();
            }
        }
    }

    public function tuifei(){
        if($_POST){
            $department = session('dpment');
            $date = $_POST['sf_date'];
            $date1 = $date.' 00:00:00';
            $date2 = $date.' 23:59:59';
            $jbxx = M('station_p');//病人基本信息
            $shouf = M('g_outp_bill_item');
            //查询历史收费情况(退费页面)
            $sfls = $shouf -> distinct(true) -> field('invoice_no,clinic_num') -> where("charge_date between '$date1' and '$date2' and bill_status = 1 and DEPARTMENT = '$department'") -> select();
            //消费总额
            $arr = array();
            for($i=0;$i<count($sfls);$i++){
                $czjine = 0;
                $pjuhao = $sfls[$i][invoice_no];
                $br_id = $sfls[$i]['clinic_num'];
                $jbxinxi = $jbxx -> where("br_id = '$br_id' and department = '$department'") -> select();
                for($j=0;$j<count($jbxinxi);$j++){
                    $arr[$i]['br_name'] = $jbxinxi[$j]['br_name'];
                    $arr[$i]['br_id'] = $jbxinxi[$j]['br_id'];
                }
                $zjine = $shouf -> field('total') -> where("invoice_no = '$pjuhao' and DEPARTMENT = '$department'") -> select();
                for($k=0;$k<count($zjine);$k++){
                    $czjine = $czjine + $zjine[$k][total];
                }
                $arr[$i]['total'] = number_format($czjine,2);
            }
            $this -> assign('arr',$arr);
            $this -> assign('sfrq',$date);
            $this -> assign('sfls',$sfls);
            $this->display();
        }else{
            $br_id = session('id');
            $xh = session('xh');
            $department = session('dpment');
            $jbxx = M('station_p');//病人基本信息
            $data = $jbxx -> where("br_id = '$br_id' and xh = '$xh' and department = '$department'") -> select();
            $shouf = M('g_outp_bill_item');
            //查询当前病人历史收费情况(退费页面)
            $sfls = $shouf -> distinct(true) -> order('invoice_no') -> field('invoice_no') -> where("CLINIC_NUM = '$br_id' and xh = '$xh' and bill_status = 1 and DEPARTMENT = '$department'") -> select();//票据号
            //消费总额
            $arr = array();
            for($i=0;$i<count($sfls);$i++){
                $czjine = 0;
                $pjuhao = $sfls[$i][invoice_no];
                $br_id = $sfls[$i]['clinic_num'];
                $zjine = $shouf -> field('total') -> where("invoice_no = '$pjuhao' and DEPARTMENT = '$department'") -> select();
                for($j=0;$j<count($zjine);$j++){
                    $czjine = $czjine + $zjine[$j][total];
                }
                $arr[$i]['total'] = number_format($czjine,2);
                $arr[$i]['br_id'] = $data[0]['br_id'];
                $arr[$i]['br_name'] = $data[0]['br_name'];
            }

            $sfrq = date('Y-m-d');
            $this -> assign('arr',$arr);
            $this -> assign('sfrq',$sfrq);
            $this -> assign('sfls',$sfls);
            $this->display();
        }
    }
    //划价收费->退费
    public function tuifei2(){
        $department = session('dpment');
        $pjh = $_POST['checked_pjh'];
        $id = $_POST['checked_id'];
        $sfb = M('g_outp_bill_item');
        $br_id = $sfb -> field('clinic_num') -> where("clinic_num = '$id' and invoice_no = '$pjh' and DEPARTMENT = '$department'") -> select();
        $br_id = $br_id[0]['clinic_num'];
        if(session('id') != $br_id){
            $res = $sfb -> where("invoice_no = '$pjh' and DEPARTMENT = '$department'") -> select();
            $len = count($res);
            $Tfei = array();
            $date = date('Y-m-d H:i:s');
            for($i=0;$i<$len;$i++){
                $up['BILL_STATUS'] = '2';
                $sfb -> where("clinic_num = '$br_id' and invoice_no = '$pjh' and DEPARTMENT = '$department'") -> save($up);
                $Tfei['BILL_CODE'] = $res[$i]['bill_code'];
                $Tfei['ITEM_CODE'] = $res[$i]['item_code'];
                $Tfei['CLINIC_NUM'] = $res[$i]['clinic_num'];
                $Tfei['xh'] = $res[$i]['xh'];
                $Tfei['CHARGE_DATE'] = $res[$i]['charge_date'];
                $Tfei['UNIT_PRICE'] = $res[$i]['unit_price'];
                $Tfei['AMOUNT'] = '-'.$res[$i]['amount'];
                $Tfei['UNITS'] = $res[$i]['units'];
                $Tfei['TOTAL'] = '-'.$res[$i]['total'];
                $Tfei['OPERATOR_CODE'] = $res[$i]['operator_code'];
                $Tfei['BILL_STATUS'] = '2';
                $Tfei['SERIAL_NO'] = $res[$i]['serial_no'];
                $Tfei['RETURN_DATE'] = $date;
                $Tfei['INVOICE_NO'] = 'T'.$res[$i]['invoice_no'];
                $Tfei['DEPARTMENT'] = $department;
                //dump($Tfei);die;
                $result = $sfb -> add($Tfei);
            }
            //dump($Tfei);die;
            if($result){
                $this -> success('病人退费成功！',U('Huajia/tuifei'),1);
            }else{
                $this -> error('系统异常，退费失败！',U('Huajia/tuifei'),2);
            }
        }else{
            $br_id = session('id');
            $jbxx = M('station_p');
            $xh = session('xh');
            $department = session('dpment');
            $res = $sfb -> where("clinic_num = '$br_id' and invoice_no = '$pjh' and DEPARTMENT = '$department'") -> select();
            $len = count($res);
            $Tfei = array();
            $date = date('Y-m-d H:i:s');
            for($i=0;$i<$len;$i++){
                $up['BILL_STATUS'] = '2';
                $sfb -> where("clinic_num = '$br_id' and invoice_no = '$pjh' and DEPARTMENT = '$department'") -> save($up);
                $Tfei['BILL_CODE'] = $res[$i]['bill_code'];
                $Tfei['ITEM_CODE'] = $res[$i]['item_code'];
                $Tfei['CLINIC_NUM'] = $res[$i]['clinic_num'];
                $Tfei['xh'] = $res[$i]['xh'];
                $Tfei['CHARGE_DATE'] = $res[$i]['charge_date'];
                $Tfei['UNIT_PRICE'] = $res[$i]['unit_price'];
                $Tfei['AMOUNT'] = '-'.$res[$i]['amount'];
                $Tfei['UNITS'] = $res[$i]['units'];
                $Tfei['TOTAL'] = '-'.$res[$i]['total'];
                $Tfei['OPERATOR_CODE'] = $res[$i]['operator_code'];
                $Tfei['BILL_STATUS'] = '2';
                $Tfei['SERIAL_NO'] = $res[$i]['serial_no'];
                $Tfei['RETURN_DATE'] = $date;
                $Tfei['INVOICE_NO'] = 'T'.$res[$i]['invoice_no'];
                $Tfei['DEPARTMENT'] = $department;
                //dump($Tfei);die;
                $result = $sfb -> add($Tfei);
            }
            if($result){
                $zy = M('prescription');
                $xy = M('xydrugcf_detial');
                $up1['indicate'] = '1';
                $up2['cf_flag'] = '1';
                $zy1 = $zy -> where("rcpt_no = '$pjh' and department = '$department'") -> select();
                if(count($zy1) != 0){
                    for($i=0;$i<count($zy1);$i++){
                        $zy -> where("rcpt_no = '$pjh' and department = '$department'") -> save($up1);
                    }
                }
                $xy1 = $xy -> where("invoice_no = '$pjh' and department = '$department'") -> select();
                if(count($xy1) != 0){
                    for($j=0;$j<count($zy1);$j++){
                        $xy -> where("invoice_no = '$pjh' and department = '$department'") -> save($up2);
                    }
                }
                $this -> success('病人退费成功！',U('Huajia/tuifei'),1);
            }else{
                $this -> error('系统异常，退费失败！',U('Huajia/tuifei'),2);
            }
        }
    }

    public function ajax(){
        $department = session('dpment');
        $sfxm = M('p_price_list');//收费项目列表
        $name = $_POST['name'];
        $sfxmdata = $sfxm -> where("item_name = '$name' and department = '$department'") -> select();
        $this -> ajaxReturn($sfxmdata,'json');
    }
    public function tuifei1(){
        $department = session('dpment');
        $sf = M('g_outp_bill_item');//收费表
        $invoice_no = $_POST['invoice_no'];
        $sflb = $sf -> where("invoice_no = '$invoice_no' and DEPARTMENT = '$department'") -> select();
        $sflbarr = array();
        $xm = M('p_price_list');
        //echo count($sflb);
        for($i=0;$i<count($sflb);$i++){
            $sflbarr[$i]['serial_no'] = $sflb[$i]['serial_no'];
            $sflbarr[$i]['id'] = $sflb[$i]['bill_code'];
            $item_code = $sflb[$i]['item_code'];
            $xmname = $xm -> field("item_name") -> where("item_code = '$item_code' and department = '$department'") -> select();
            $sflbarr[$i]['item_name'] = $xmname[0]['item_name'];
            $sflbarr[$i]['units'] = $sflb[$i]['units'];
            $sflbarr[$i]['qufenleibie'] = $sflb[$i]['qufenleibie'];
            $sflbarr[$i]['unit_price'] = number_format($sflb[$i]['unit_price'],2);
            $sflbarr[$i]['amount'] = number_format($sflb[$i]['amount'],2);
            // $sflbarr[$i]['total'] = $sflbarr[$i]['unit_price'] * $sflbarr[$i]['amount'];
            $sflbarr[$i]['total'] = number_format($sflb[$i]['total'],2);
            $sflbarr[$i]['ztotal'] += $sflbarr[$i]['total'];
            $sflbarr[$i]['ztotal'] = number_format($sflbarr[$i]['ztotal'],2);
        }
        // dump($sflbarr);die;
        $this -> ajaxReturn($sflbarr,'json');
    }
    public function yplist(){
        $department = session('dpment');
        $sf = M('g_outp_bill_item');//收费表
        $price = M('p_price_list');//价格表
        $zy = M('prescription');//中药表
        $zymx = M('prescription_detail');//中药明细表
        $xy = M('xydrugcf_detial');//西药表
        $id = $_POST['id'];
        $len = $_POST['len'];
        $sfdetail = array();
        if($len == '1'){//中药
            $zyid = $zy -> where("presc_no =  '$id' and department = '$department'") -> select();
            if(count($zyid) != 0){
                for($i=0;$i<count($zyid);$i++){
                    $presc_no = $zyid[$i]['presc_no'];
                    $zymx_d = $zymx -> where("presc_no = '$presc_no' and department = '$department'") -> select();
                    for($j=0;$j<count($zymx_d);$j++){
                        $sfdetail[$j]['id'] = $j+1;
                        $sfdetail[$j]['xmname'] = $zymx_d[$j]['drug_name'];
                        $sfdetail[$j]['amount'] = number_format($zymx_d[$j]['amount'],2);
                        $sfdetail[$j]['units'] = $zymx_d[$j]['drug_units'];
                        $sfdetail[$j]['dose'] = $zyid[0]['dose'];
                        $sfdetail[$j]['price'] = number_format($zymx_d[$j]['price'],2);
                        if($sfdetail[$j]['price'] == null){
                            $sfdetail[$j]['price'] = 0.00;
                        }
                        $sfdetail[$j]['costs'] = $sfdetail[$j]['amount'] * $sfdetail[$j]['dose'] * $sfdetail[$j]['price'];
                        $sfdetail[$j]['costs'] = number_format($sfdetail[$j]['costs'],2);
                    }
                }
            }
        }else{//西药
            $xyid = $xy -> where("cf_id = '$id' and department = '$department'") -> select();
            if(count($xyid) != 0){
                for($i=0;$i<count($xyid);$i++){
                    $sfdetail[$i]['id'] = $i+1;
                    $sfdetail[$i]['xmname'] = $xyid[$i]['yp_name'];
                    $sfdetail[$i]['amount'] = number_format($xyid[$i]['yp_total_amount'],2);
                    $sfdetail[$i]['units'] = $xyid[$i]['yp_spec'];
                    $sfdetail[$i]['dose'] = '';
                    $sfdetail[$i]['price'] = number_format($xyid[$i]['price'],2);
                    if($sfdetail[$i]['price'] == null){$sfdetail[$i]['price'] = 0.00;}
                    $sfdetail[$i]['costs'] = $sfdetail[$i]['amount'] * $sfdetail[$i]['price'];
                    $sfdetail[$i]['costs'] = number_format($sfdetail[$i]['costs'],2);
                }
            }
        }
        $this -> ajaxReturn($sfdetail,'json');
    }


    //完成就诊
    public function jieshu(){
        $br_id = $_GET['br_id'];
        $xh = $_GET['xh'];
        //销毁session
        session('id',null);
        session('xh',null);
        session('tzbsInfo',null);
        session('jkdaSave',null);//判断是否保存过
        session('save',null);    //判断是否保存过体质辨识
        //将station_p表的jz_flag字段值改为2
        $department = session('dpment');
        $sta = M('station_p');
        $sta -> where("br_id = '$br_id' and xh = '$xh' and department = '$department'") -> setField('jz_flag','2');
        if($sta){
            $a = 1;
        }else{
            $a = 0;
        }
        $this -> ajaxReturn($a,'json');
    }

    //收费项目检索
    public function sfxmjs(){
        $str = $_POST['str'];
        $list = M('p_price_list');
        $dep = M('user_info_dict');
        $res = $dep -> field('department') -> where("id =".session(wh_userId)) -> select();
        $department = $res[0]['department'];
        if($str == ''){
            return false;
        }
        $res = $list -> where("input_code like'".$str."%' and enabled_sign = 1 and department = '$department'") -> order('input_code') -> select();
        $this -> ajaxReturn($res,'json');
    }
}