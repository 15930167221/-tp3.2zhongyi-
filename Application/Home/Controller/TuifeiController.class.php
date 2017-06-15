<?php
namespace Home\Controller;

class TuifeiController extends PublicController {

    protected function __initialize()
    {
        parent::_initialize();
    }

    public function index(){
        $department = session('dpment');
        if($_POST){
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
            $sfls = $shouf -> distinct(true) -> order('invoice_no') -> field('invoice_no') -> where("CLINIC_NUM = '$br_id' and xh = '$xh' and bill_status = 1 and department = '$department'") -> select();//票据号
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

    public function tuifei1(){
        $department = session('dpment');
        $sf = M('g_outp_bill_item');//收费表
        $invoice_no = $_POST['invoice_no'];
        $sflb = $sf -> where("invoice_no = '$invoice_no' and DEPARTMENT = '$department'") -> select();
        $sflbarr = array();
        $xm = M('p_price_list');
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
        $this -> ajaxReturn($sflbarr,'json');
    }

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
                $result = $sfb -> add($Tfei);
            }
            if($result){
                $this -> success('病人退费成功！',U('Tuifei/index'),1);
            }else{
                $this -> error('系统异常，退费失败！',U('Tuifei/index'),2);
            }
        }else{
            $br_id = session('id');
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
            //dump($Tfei);die;
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
                $this -> success('病人退费成功！',U('Tuifei/index'),1);
            }else{
                $this -> error('系统异常，退费失败！',U('Tuifei/index'),2);
            }
        }
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
}