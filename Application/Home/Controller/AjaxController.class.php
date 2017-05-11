<?php
namespace Home\Controller;

class AjaxController extends PublicController {

    protected function __initialize()
    {
        parent::_initialize();
    }
      public function power($power){
        $pow = M('power-name');
        $list = $pow->where("pid=$power")->select();
        $this->ajaxReturn($list);
      }
      public function upload($id){
        $user = M('user_info_dict');
        $userinfo = $user->where("id=$id")->select();
        $this->ajaxReturn($userinfo);
      }
      public function drugWest($htm){
        $durg = M('drug_dict');
        $ke = M('sys_dm_jldw');
        if(preg_match("/^[a-z]/i", $htm)){
          $where['drug_dict.input_code'] = array('like',"{$htm}%");

          $where['drug_dict.drug_indicator'] = array('in','01,03');
          $dpment = session('dpment');
          $where['drug_dict.department'] = $dpment;
          $where['drug_dict.enable_flag'] = 0;

          $list = $durg->where($where)->join('sys_dm_jldw on sys_dm_jldw.id = drug_dict.hl_unit')->field('drug_dict.drug_name,drug_dict.drug_code,drug_dict.input_code,drug_dict.drug_spec,drug_dict.price,drug_dict.hl,sys_dm_jldw.dw as units')->select();
          // for($i=0;$i<count($list);$i++){
          //   $a = $ke->where("dwdm=$list[$i]['units']")->field('dw')->select();
          //   $list[$i]['units'] = $a[0]['dw'];
          // }
      }else{
          $where2['drug_dict.drug_name'] = array('like',"{$htm}%");
          $where2['drug_dict.drug_indicator'] = array('in','01,03');
            $list = $durg->where($where2)->join('sys_dm_jldw on sys_dm_jldw.id = drug_dict.hl_unit')->field('drug_dict.drug_name,drug_dict.drug_code,drug_dict.input_code,drug_dict.drug_spec,drug_dict.price,drug_dict.hl,sys_dm_jldw.dw as units')->select();
      }
       
        $this->ajaxReturn($list);
      }
      public function sele(){
        // $a = $_POST['list'];
        $drug = M('drug_dict');
        $ke = M('sys_dm_jldw');
        $list = $drug->where("drug_code='$_POST[list]'")->find();
        $jlcode = $list['package_units'];
        $hlcode = $list['hl_unit'];
        if($list['hl_unit']){
          $hlcode = $list['hl_unit'];
        }else{
           $hlcode = '9583';
        }
        $danwei = $ke->where("dwdm=$hlcode")->field('dw')->select();

        $bzdwcode = $list['bzdw2'];
        $bzdw =  $ke->where("dwdm=$bzdwcode")->field('dw')->select();;
        $list['hl'] = $list['hl'];//.$danwei[0]['dw']
        $list['bzsl2'] = $list['bzsl2'];//.$bzdw[0]['dw']
        $list['sldw'] = $bzdw[0]['dw'];
        $list['xdw'] = $danwei[0]['dw'];

        $this->ajaxReturn($list);
      }

      //西药处方处理
      public function Chufang(){
//        session('xyFlag',1);//判断健康档案，西医诊断的来源
       
        $bianma = $_POST[bianma];
        $mingcheng = $_POST[mingcheng];
        $guige = $_POST[guige];
        $hanliang = $_POST[hanliang];
        $baozhuang = $_POST[baozhuang];
        $shuliang = $_POST[shuliang];
        $zongliang = $_POST[zongliang];
        $tujing = $_POST[tujing];
        $yongliang = $_POST[yongliang];
        $shuliang = $_POST[shuliang];
        $cishu = $_POST[cishu];
        $tsyf = $_POST[tsyf];
        $tianshu = $_POST[tianshu];
        $zdw = $_POST[zdw];
        $bz = $_POST[bz];

        $abianma = explode(',',$_POST[bianma]);
        $amingcheng = explode(',',$_POST[mingcheng]);
        $aguige = explode(',',$_POST[guige]);
        $ahanliang = explode(',',$_POST[hanliang]);
        $abaozhuang = explode(',',$_POST[baozhuang]);
        $ashuliang = explode(',',$_POST[shuliang]);
        $azongliang = explode(',',$_POST[zongliang]);
        $atujing = explode(',',$_POST[tujing]);
        $ayongliang = explode(',',$_POST[yongliang]);
        $ashuliang = explode(',',$_POST[shuliang]);
        $acishu = explode(',',$_POST[cishu]);
        $atsyf = explode(',',$_POST[tsyf]);
        $atianshu = explode(',',$_POST[tianshu]);
        $azdw = explode(',',$_POST[zdw]);

        $num = count(explode(',',$bianma))-1;
        
          $xydict = M('xydrugcf_detial');
              $cfi = (string)$_POST[cfid];
       
          $today = date("Y-m-d H:i:s");
          $brId = $_SESSION['id'];
          $xydict->where("cf_id='$cfi'")->delete();
          for($i = 0; $i < $num; $i++){
          $list[$i]['doctor_id'] = $_SESSION['wh_userId'];
          $list[$i]['yp_mem'] = $_POST[bz];
          
        
        
          
          $list[$i]['cf_id'] = $cfi;
          $list[$i]['cf_date'] = $today;
          $list[$i]['BR_ID'] = (string)$brId;
          $list[$i]['yp_code'] = (string)$abianma[$i+1];
          $list[$i]['yp_name'] = (string)$amingcheng[$i+1];
          $list[$i]['yp_spec'] = (string)$aguige[$i+1];
          $list[$i]['rl'] = $ahanliang[$i+1];
          $list[$i]['yp_total_amount'] = $ashuliang[$i+1];
          $list[$i]['zl'] = (string)$azongliang[$i+1];
          $list[$i]['yp_useage'] = (string)$atujing[$i+1];
          $list[$i]['yp_yc_amount'] = (string)$ayongliang[$i+1];
          $list[$i]['yp_pl_day'] = (string)$acishu[$i+1];
          $list[$i]['bzsl2'] = (string)$abaozhuang[$i+1];
          $list[$i]['yp_rldw'] = (string)$azdw[$i+1];
          $list[$i]['yp_speci_use_name'] = (string)$atsyf[$i+1];

          $list[$i]['fs'] = $atianshu[$i+1];
          $list[$i]['cf_flag'] = (string)1;

          $list[$i]['xy_name'] = (string)$_POST[xybm];
          $list[$i]['XH'] = session(xh);
        }

          for($j = 0; $j < $num; $j++){
            $xydict->data($list[$j])->add();
          }
         
          $this->ajaxReturn(1);
      }
      //西药病名
      public function westBing(){
          $val = $_POST[val];
          $xy = M('xy_name');
        if(preg_match("/^[a-z]/i", $val)){
          $where['spell'] = array('like',"{$val}%");
          $list = $xy->where($where)->select();
        }else{
          $where['name'] = array('like',"{$val}%");
          $list = $xy->where($where)->select();
        };
         $this->ajaxReturn($list);
      }

      //用户密码重置
      
      public function reset(){
        $id = $_POST[id];
        $user = M('user_info_dict');
        $data['passWord'] = '123456';
        $user->where("id=$id")->save($data);
        $this->ajaxReturn(1);
      }

      //个人信息修改
      public function updateU(){
        $id = $_POST[id];
        $user = M('user_info_dict');
        $list = $user->where("id=$id")->find();
        $this->ajaxReturn($list);
      }

      //信息修改操作
      public function updateUser(){
        $id = $_POST[id];
        $name = $_POST[name];
        $phone = $_POST[phone];
        $pass = $_POST[pass];
        $user = M('user_info_dict');
        $data['userName'] = $name;
        $data['userPhone'] = $phone;
        $data['passWord'] = $pass;
        $user->where("id=$id")->save($data);
        $this->ajaxReturn(1);

      }
      //中医药品查询
      public function zysele(){
        $val = $_POST[val];
         $durg = M('drug_dict');
        if(preg_match("/^[a-z]/i", $val)){
          $where['input_code'] = array('like',"{$val}%");
          $where['drug_indicator'] = '02';

          $where['department'] = session('dpment');
          $list = $durg->where($where)->where("drug_indicator=2")->field('drug_name')->select();
      }else{
          $where2['drug_name'] = array('like',"{$val}%");
          $where2['drug_indicator'] = 2;
          $where2['department'] = session('dpment');
          $list = $durg->where($where2)->field('drug_name')->select();
      }

       
        $this->ajaxReturn($list);
      }
      //加药
      public function jia(){
        $name = $_POST['val'];
        $drug = M('drug_dict');
        $list = $drug->where("drug_code='$name'")->Field("drug_code,xw1")->find();
        $this->ajaxReturn($list);
      }

      //检查是不是孕妇
      public function women(){
        //孕妇
        $codeList = $_POST[val];
        for($i = 0;$i<count($codeList);$i++){
          $code[$i] = $codeList[$i];
        }
        $whe['XXDM'] = array('in',$code);
        $whe['XXLBDM'] = '12';
        $dict = M('jbxx');
        $list['women'] = $dict->where($whe)->select();
        $b=0;
        for($a =0;$a<count($list['women']);$a++){
          if($list['women'][$a]['bz']=='禁用'){
            $b++;
          }
        }
        if($b>0){
          $list['jy'] = 1;
        }else{
          $list['jy'] =0;
        }
        //超标
        session('cz',1);  //健康档案处置判断取值位置（1处方0.bqxx）
        $ypCodeList=I("post.val");
        $ypYlList=I("post.ypyl");
        $res="";
        $jbxx=M('jbxx');//药品用量表
        $clyp=$jbxx->where("xxlbdm='03'")->select();//规定的超量药品数据
        $xxdm=i_array_column($clyp,'xxdm');//二维数组转一维数组
        $comm=array_intersect($ypCodeList,$xxdm);//找出交集
        foreach($comm as $key=>$value){
            $num=array_keys($ypCodeList,$value);///$num为一个数组,确定交集中元素的位置
            $ypYl=$jbxx->where("xxlbdm='03' and xxdm='".$value."'")->find();//标准
            if((int)$ypYl['bz']<(int)$ypYlList[$num[0]]){
                $res.="第".($num[0]+1)."味药【".$ypYl['xxmc']."】的用量超过范围（".$ypYl['bz']."克）!!!<br/>";
            }
        }
        $list['over'] = $res;
        //有毒
        $code = $_POST['val'];
        $where['dict_drug_zy_mx.drug_code'] = array('in',$code);
        $where['dict_drug_zy_mx.department'] = session('dpment');
        $dict = M('dict_drug_zy_mx');
        $dpment = session('dpment');
        $list['du'] = $dict->where($where)->where("drug_dict.department=$dpment and dict_drug_zy_mx.dx>0")->join('drug_dict on drug_dict.drug_code=dict_drug_zy_mx.drug_code')->field('dict_drug_zy_mx.dx,dict_drug_zy_mx.drug_code as code,drug_dict.drug_name,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(dict_drug_zy_mx.zysx)) as zysx')->select();
        //18反19畏
        $codeLista = $_POST[val];
        $dict = M('jy');
        $nn = 0;
          foreach($codeLista as $key => $val) {
              array_shift($codeLista);
              foreach($codeLista as $k => $v) {
                  $sql = "select * from jy where (YP1 = '$val' and YP2 = '$v') or (YP1='$v' and YP2='$val') ";
                  $data = $dict->query($sql);
                  if(count($data)>0){
                      $ret[$nn] = $data[0];
                      $nn++;
                  }
              }
          }
          $list['fw'] = $ret;
           if(count($list['du'])>0){
            $a = 1;
          }elseif (count($list['fw'])>0) {
            $a = 1;
          }elseif(count($list['women'])>0){
            $a = 1;
          }elseif($list['over']!==''){
            $a = 1;
          }else{
            $a = 0;
          }
          $list['two'] = $a;
        //
        $this->ajaxReturn($list);
      }
       public function women12(){
    
        //超标
        session('cz',1);  //健康档案处置判断取值位置（1处方0.bqxx）
        $ypCodeList=I("post.val");
        $ypYlList=I("post.ypyl");
        $res="";
        $jbxx=M('jbxx');//药品用量表
        $clyp=$jbxx->where("xxlbdm='03'")->select();//规定的超量药品数据
        $xxdm=i_array_column($clyp,'xxdm');//二维数组转一维数组
        $comm=array_intersect($ypCodeList,$xxdm);//找出交集
        foreach($comm as $key=>$value){
            $num=array_keys($ypCodeList,$value);///$num为一个数组,确定交集中元素的位置
            $ypYl=$jbxx->where("xxlbdm='03' and xxdm='".$value."'")->find();//标准
            if((int)$ypYl['bz']<(int)$ypYlList[$num[0]]){
                $res.="第".($num[0]+1)."味药【".$ypYl['xxmc']."】的用量超过范围（".$ypYl['bz']."克）!!!<br/>";
            }
        }
        $list['over'] = $res;
        //有毒
        $code = $_POST['val'];
        $where['dict_drug_zy_mx.drug_code'] = array('in',$code);
        $where['dict_drug_zy_mx.department'] = session('dpment');
        $dict = M('dict_drug_zy_mx');
        $dpment = session('dpment');
        $list['du'] = $dict->where($where)->where("drug_dict.department=$dpment and dict_drug_zy_mx.dx>0")->join('drug_dict on drug_dict.drug_code=dict_drug_zy_mx.drug_code')->field('dict_drug_zy_mx.dx,dict_drug_zy_mx.drug_code as code,drug_dict.drug_name,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(dict_drug_zy_mx.zysx)) as zysx')->select();
        //18反19畏
        $codeLista = $_POST[val];
        $dict = M('jy');
        $nn = 0;
          foreach($codeLista as $key => $val) {
              array_shift($codeLista);
              foreach($codeLista as $k => $v) {
                  $sql = "select * from jy where (YP1 = '$val' and YP2 = '$v') or (YP1='$v' and YP2='$val') ";
                  $data = $dict->query($sql);
                  if(count($data)>0){
                      $ret[$nn] = $data[0];
                      $nn++;
                  }
              }
          }
          $list['fw'] = $ret;
          if(count($list['du'])>0){
            $a = 1;
          }elseif (count($list['fw'])>0) {
            $a = 1;
          }elseif(count($list['women'])>0){
            $a = 1;
          }elseif($list['over']!==''){
            $a = 1;
          }else{
            $a = 0;
          }
          $list['two'] = $a;
        //
        $this->ajaxReturn($list);
      }
     //检查18反19畏
      public function fanwei(){
        $codeLista = $_POST[val];
//        for($i = 0;$i<count($codeLista);$i++){
//          $code[$i] = $codeLista[$i];
//        }
        $dict = M('jy');
        $nn = 0;
          foreach($codeLista as $key => $val) {
              array_shift($codeLista);
              foreach($codeLista as $k => $v) {
                  $sql = "select * from jy where (YP1 = '$val' and YP2 = '$v') or (YP1='$v' and YP2='$val') ";
                  $data = $dict->query($sql);
                  if(count($data)>0){
                      $ret[$nn] = $data[0];
                      $nn++;
                  }
              }
          }
        $this->ajaxReturn($ret);
      }
    //药品超量检查
    public function amountCheck(){
        session('cz',1);  //健康档案处置判断取值位置（1处方0.bqxx）
        $ypCodeList=I("post.code");
        $ypYlList=I("post.ypyl");
        $res="";
        $jbxx=M('jbxx');//药品用量表
        $clyp=$jbxx->where("xxlbdm='03'")->select();//规定的超量药品数据
        $xxdm=i_array_column($clyp,'xxdm');//二维数组转一维数组
        $comm=array_intersect($ypCodeList,$xxdm);//找出交集
        foreach($comm as $key=>$value){
            $num=array_keys($ypCodeList,$value);///$num为一个数组,确定交集中元素的位置
            $ypYl=$jbxx->where("xxlbdm='03' and xxdm='".$value."'")->find();//标准
            if((int)$ypYl['bz']<(int)$ypYlList[$num[0]]){
                $res.="第".($num[0]+1)."味药【".$ypYl['xxmc']."】的用量超过范围（".$ypYl['bz']."克）!!!<br/>";
            }
        }
        $this->ajaxReturn($res);
//        $this->ajaxReturn($ypYlList[$num[0]]);
//        $this->ajaxReturn($ypYlList[10]);
//        $this->ajaxReturn($ypYl['bz']-$ypYlList[10]);
    }
    //获取ID
    public function getId(){
       //获取当日处方最大的ID
          $cf_dict = M('prescription');
          $day = date('ymd');
          $where['presc_no'] = array('like',"{$day}%");
          $mustId = $cf_dict->where($where)->order("presc_no desc")->Field('presc_no')->find();
          $newId = $mustId['presc_no']+1;
          if(count($_POST['cfcode'])>1){
            $newId = $_POST['cfcode'];
          }
          if($newId == 1){
            $newId = $day.'00001';
          }
          $this->ajaxReturn($newId);
    }
      //中医处方保存
      public function zyBaocun(){
        $cf_dict = M('prescription');
        $cf_info = M('prescription_detail');
        $cfCode = (string)$_POST[cfcode];
        $ww['presc_no']=$cfCode;
        //原处方中的处方删除保存新的
        $cf_dict->where($ww)->delete();
        $cf_info->where($ww)->delete();
        $ypxuhao = $_POST[ypxuhao];
        $zycode = $_POST[zycode];
        $yaoming = $_POST[yaoming];
        $ypkg = $_POST[ypkg];
        $jianfa = $_POST[jianfa];
        $dw = $_POST[dw];
        $day = date('ymd');
          //获取当日处方最大的ID
          $where['presc_no'] = array('like',"{$day}%");
          $mustId = $cf_dict->where($where)->order("presc_no desc")->Field('presc_no')->find();
          $newId = $mustId['presc_no']+1;
          if((int)$cfCode>1){
            $newId = $cfCode;
          }
          if($newId == 1){
            $newId = $day.'00001';
          }
          $num = count($yaoming);
          for ($i=0; $i <$num ; $i++) { 
            $data[$i]['presc_no'] = (string)$newId;
            $data[$i]['drug_no'] = $ypxuhao[$i];
            $data[$i]['drug_code'] = (string)$zycode[$i];
            $data[$i]['drug_name'] = (string)$yaoming[$i];
            $data[$i]['amount'] = (string)$ypkg[$i];
            $data[$i]['usage'] = (string)$jianfa[$i];
            $data[$i]['drug_units'] = '克';
          }

          for($j = 0; $j < $num; $j++){
            $cf_info->data($data[$j])->add();
          }
          $newData['presc_no'] = (string)$newId;
          $newData['patient_id'] = (string)$_SESSION[id];
          $newData['presc_date'] = date("Y-m-d H:i:s");
          $newData['operator'] = (string)$_SESSION[wh_userName];
          $newData['pregnant_woman'] = $_POST[yunfu];
          $newData['dose'] = $_POST[jiliang];
          $newData['order_text'] = (string)$_POST[yizhu];
          $newData['cf_tree'] = (string)$_POST[cftree];
          $newData['xh'] = $_SESSION[xh];
          $newData['presc_name'] = (string)$_POST[cfname];
          $newData['zy_name'] = (string)$_POST[bm];
          $newData['usage1'] = (string)$_POST[yf1]; 
          $newData['decoction'] = (string)$_POST[jf1]; 
          $newData['dosage'] = (string)$_POST[yl1]; 
          $newData['presc_name'] = (string)$_POST[cfm]; 
          $newData['bz'] = (string)$_POST[zx]; 
          $newData['zz'] = (string)$_POST[zf]; 
          $newData['doctor_id'] = $_SESSION[wh_userId];
          $newData['department'] = $_SESSION['dpment']; 
          $cf_dict->data($newData)->add();

          $this->ajaxReturn($cfCode);
      }

      // 保存处方至审核表
      public function baoSh(){
          $cf_dict = M('dshdict');
        $cf_info = M('dshdict_detail');
         $cfCode = (string)$_POST[cfcode];
        $ww['presc_no']=$cfCode;
        //原处方中的处方删除保存新的
        $cf_dict->where($ww)->delete();
        $cf_info->where($ww)->delete();
        $ypxuhao = $_POST[ypxuhao];
        $zycode = $_POST[zycode];
        $yaoming = $_POST[yaoming];
        $ypkg = $_POST[ypkg];
        $jianfa = $_POST[jianfa];
        $dw = $_POST[dw];
        $day = date('ymd');
          //获取当日处方最大的ID
          $where['presc_no'] = array('like',"{$day}%");
          $mustId = $cf_dict->where($where)->order("presc_no desc")->Field('presc_no')->find();
          $newId = $mustId['presc_no']+1;
          if($newId == 1){
            $newId = $day.'00001';
          }
          $num = count($yaoming);
          for ($i=0; $i <$num ; $i++) { 
            $data[$i]['presc_no'] = (string)$_POST[cfcode];
            $data[$i]['drug_no'] = $ypxuhao[$i];
            $data[$i]['drug_code'] = (string)$zycode[$i];
            $data[$i]['drug_name'] = (string)$yaoming[$i];
            $data[$i]['amount'] = (string)$ypkg[$i];
            $data[$i]['usage'] = (string)$jianfa[$i];
            $data[$i]['drug_units'] = '克';
          }

          for($j = 0; $j < $num; $j++){
            $cf_info->data($data[$j])->add();
          }
          $newData['presc_no'] = (string)$_POST[cfcode];
          $newData['patient_id'] = (string)$_SESSION[id];
          $newData['presc_date'] = date("Y-m-d H:i:s");
          $newData['tj_date'] = date("Y-m-d H:i:s");
          $newData['operator'] = (string)$_SESSION[wh_userName];
          $newData['pregnant_woman'] = $_POST[yunfu];
          $newData['dose'] = $_POST[jiliang];
          $newData['order_text'] = (string)$_POST[yizhu];
          $newData['cf_tree'] = (string)$_POST[cftree];
          $newData['xh'] = $_SESSION[xh];
          $newData['presc_name'] = (string)$_POST[cfname];
          $newData['zy_name'] = (string)$_POST[bm];
          $newData['usage1'] = (string)$_POST[yf1]; 
          $newData['decoction'] = (string)$_POST[jf1]; 
          $newData['dosage'] = (string)$_POST[yl1]; 
          $newData['presc_name'] = (string)$_POST[cfm]; 
          $newData['bz'] = (string)$_POST[zx]; 
          $newData['zz'] = (string)$_POST[zf];
          $newData['doctor_id'] = $_SESSION[wh_userId]; 
          $newData['indicate'] = 0; 
          $newData['department'] = $_SESSION['dpment']; 


          $cf_dict->data($newData)->add();

          $this->ajaxReturn($newId);
      }

//    有毒药品检测
      public function drugDu(){
        $code = $_POST['code'];
        $where['dict_drug_zy_mx.drug_code'] = array('in',$code);
        $where['dict_drug_zy_mx.department'] = session('dpment');
        $dict = M('dict_drug_zy_mx');
        $dpment = session('dpment');
     

        // for($i = 0;$i<count($code);$i++){
        //   $list.=$dict->where("drug_code=$code[$i]")->field('dx')->select();
        // }
        $list = $dict->where($where)->where("drug_dict.department=$dpment")->join('drug_dict on drug_dict.drug_code=dict_drug_zy_mx.drug_code')->field('dict_drug_zy_mx.dx,dict_drug_zy_mx.drug_code as code,drug_dict.drug_name,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(dict_drug_zy_mx.zysx)) as zysx')->select();
        $this->ajaxReturn($list);
      }



//    历史处方ajax
    public function hisPreAjax(){
        $no=I('post.drugNo');//处方号
        $pre=M('prescription');//处方对应的药品信息表
        $drugInf=$pre->join('prescription_detail on prescription_detail.presc_no=prescription.presc_no')->join('drug_dict on prescription_detail.drug_code=drug_dict.drug_code')->field('prescription.order_text,prescription.usage1,prescription.decoction,prescription.dosage,prescription_detail.drug_code,prescription_detail.drug_name,prescription_detail.amount,prescription_detail.drug_units,prescription_detail.usage,drug_dict.xw1')->where("prescription_detail.presc_no='$no'")->select();
        $this->ajaxReturn($drugInf,'json');
    }
    //双击复制历史处方
    public function sjFuzhi(){
      $cfh = $_POST[cfh];
      // $dict = M('prescription_detail');
      $zdict = M('prescription');
      $dpment = session('dpment');
      $where['department'] = $dpment;
      $list = $zdict->join('prescription_detail on prescription_detail.presc_no=prescription.presc_no')->join('drug_dict on prescription_detail.drug_code=drug_dict.drug_code')->field('prescription.order_text,prescription.usage1,prescription.decoction,prescription.dosage,prescription_detail.drug_name,prescription_detail.drug_code,prescription_detail.amount,prescription_detail.drug_units,prescription.presc_name,prescription_detail.usage,drug_dict.xw1')->where($where)->where("prescription_detail.presc_no='$cfh'")->select();
      // $name = $zdict->where("presc_no=$cfh")->field("presc_name")->select();
      // $list[0]['name']= $name[0]['presc_name'];
      $this->ajaxReturn($list);
    }
    //经典处方
    public function jdSel(){
      $val = $_POST[val];
      $dict = M();

        $sql = "SELECT  [TREE], NAME FROM V_RECIPEMAIN WHERE [SPELL] LIKE '%$val%' order by type,tree";
        $list = $dict->query($sql);
//        echo $dict->getLastSql();
      $this->ajaxReturn($list);
    }
    //取经典所有
    public function jdOne(){
      $tree = $_POST[tree];
      $dict = M('bz_cf');
      $where['bz_cf.CFDM'] = $tree;
      $dpment = session('dpment');
      $list = $dict->join('drug_dict on drug_dict.drug_code = bz_cf.ypdm')->where($where)->where("drug_dict.department=$dpment")->field('drug_dict.drug_name,drug_dict.xw1,drug_dict.drug_code')->select();

        $this->ajaxReturn($list);
    }
    //查看不同
    public function sameDif(){
      $a = $_POST[aa];
      $b = $_POST[bb];
      $c = array_merge(array_diff($a,$b),array_diff($b,$a));
      $dict = M('drug_dict');
      $where['drug_code'] = array('in',$c);
      $dpment = session('dpment');
      $dilist = $dict->where($where)->where("drug_dict.department=$dpment")->field('drug_name')->select();
      $this->ajaxReturn($dilist);
    }
    //查看相同
    public function diffSam(){
      $a1 = $_POST[cc];
      $b1 = $_POST[dd];
      $same = array_intersect($a1,$b1);
      $dict = M('drug_dict');
      $dpment = session('dpment');
      $where['drug_code'] = array('in',$same);
      $salist = $dict->where($where)->where("drug_dict.department=$dpment")->field('drug_name')->select();
      $this->ajaxReturn($salist);
    }
    //点击通过审核按钮执行的操作
    public function shenhe(){
        $cfNum=I('post.code');
        $pre=M('Prescription');
       
        //要更新的字段
        $data['indicate']=1;
        $condition['presc_no']=$cfNum;
         //检查处方是否保存
        $num = $pre->where($condition)->field('presc_no')->find();
        if($num){
          $res=$pre->where($condition)->save($data);
        if($res){
            $this->ajaxReturn('审核成功！');
        }else{
            $this->ajaxReturn('审核失败！');
        }
      }else{
           $this->ajaxReturn('请您先保存处方！');
      }
        
    }
    //详细的处方
    public function cfDetail(){
      $code = $_POST[code];
      $where['presc_no'] = $code;
      $where['drug_dict.department'] = session('dpment');
      $dict = M('prescription_detail');
      $dict2 = M('prescription');
      $list = $dict->where($where)->join('drug_dict on drug_dict.drug_code=prescription_detail.drug_code')->field('prescription_detail.drug_name,prescription_detail.drug_code as code,drug_dict.xw1,prescription_detail.drug_units,prescription_detail.usage,prescription_detail.amount')->select();
      $res = $dict2->where("presc_no=$code")->field('order_text')->select();
      $list[0]['yz'] = $res[0]['order_text'];
      $this->ajaxReturn($list);
    }
    //西药修改
    public function xyXiug(){
      $id = $_POST['cfh'];
      $dict = M('xydrugcf_detial');
      $list = $dict->where("cf_id='$id'")->select();
      for ($i=0; $i < count($list) ; $i++) {
        $a = preg_replace('/[^\.0123456789]/s', '', $list[$i]['yp_yc_amount']); 
        $b = str_replace(array($a),"",$list[$i]['yp_yc_amount']);
        $list[$i]['yp_yc_amount'] =$a.'+'.$b;
        // $list[$i]['yp_yc_amount'][1] = 'bb';
      }
      $this->ajaxReturn($list);
    }
    //修改处方
    public function upDateCf(){
      $cfId = $_POST[cfh];
      $dict = M('xydrugcf_detial');
      $dict->where("cf_id=$cfId")->delete();
      $this->ajaxReturn(1);
    }
    //存经验方
    public function cunJy(){
       $spell=M('dict_hzpy');
        $data=$_POST;
        $split=str_split($data[name],3);
        foreach ($split as $v){
            $con[]=$spell->where("BHZ='$v'")->find();
        }
        foreach ($con as $v){
            $pym[]=$v['bsm'];
        }
        $pym=implode('',$pym);

        $model=M('experience');
        $data[Attending]=trim($data[Attending]);
        $data[input_code]=$pym;
        $data[operator_code]=session(wh_userId);
        $data[create_date]=date(Ymd);
        $info=$model->add($data);
        $num = count($_POST[drug_no]);
        $dict = M('experience_detail');
        for($i = 0;$i<$num;$i++){
          $newData[$i]['id'] = intval($info);
          $newData[$i]['drug_no'] = $_POST['drug_no'][$i];
          $newData[$i]['drug_name'] = $_POST['zyname'][$i];
          $newData[$i]['drug_code'] = $_POST['code'][$i];
          $newData[$i]['drug_units'] ='克';
          $newData[$i]['usage'] = $_POST['usage'][$i];
          $newData[$i]['amount'] = $_POST['amount'][$i];
        }
        for ($j=0; $j < $num; $j++) { 
          $list = $dict->add($newData[$j]);
        }
        $this->ajaxReturn(111);
    }
    //自定义开方删除处方
    public function deleteCf(){
      $id = (string)$_POST[cfid];
      $where['presc_no'] = $id;
      $dict = M('prescription');
      $dict2 = M('prescription_detail');
      $dict->where($where)->delete();
      $dict2->where($where)->delete();
      $this->ajaxReturn('删除成功');
    }
    //已审核变未审核
    // public function shChange(){
    //   $id =$_POST['id'];
    //   $dict = M('prescription');
    //   $data['indicate'] = 0;
    //   $dict->where("presc_no=$id")->save($date);
    // }
    //更改审核状态
    public function resSh(){
      $where['presc_no'] = $_POST['code'];
      // $data['indicate'] = 0;
      $dict = M('prescription');
      // $res = $dict->where($where)->save($data);
      $dict2= M('prescription_detail');
      $res = $dict->where($where)->delete();
      $res2 = $dict2->where($where)->delete();
      echo $res.$res2;      
    }

    //查询随症加减
    public function szjj(){
      $code = $_POST[tree];
      $cf_tree = explode(' ',$code);
      $dict = M('tcd_szjj');
        for($j = 0;$j < count($cf_tree); $j++){
                 $szjj[$j] = $dict->where("CF_TREE='$cf_tree[$j]'")->select();
                if(count($szjj[$j])<1){
                    $szjj[$j] = 999;
                }
            }
      // $list = $dict->where("CF_TREE=$code")->select();
      $this->ajaxReturn($szjj);
    }

    //处方预审选中病历号查看处方详细信息
    public function ys(){
        $cfid=I("post.cfid");
        $xy=M('xydrugcf_detial');
        $condition['cf_id']=$cfid;
        $condition['drug_dict.department']=$_SESSION[dpment];
        $xydrug=$xy->field("drug_dict.drug_name,drug_dict.drug_spec,yp_total_amount,drug_dict.price")->where($condition)->join("drug_dict on yp_code=drug_code")->select();
        foreach($xydrug as $key=>$val){
            $xyInf[$key]['yp_name']=$val['drug_name'];                 //药品名称
            $xyInf[$key]['num']=floatval($val['yp_total_amount']);     //数量
            $xyInf[$key]['dw']=substr(trim($val['drug_spec']),-3);     //单位
            $xyInf[$key]['js']=1;                                         //剂数
            $xyInf[$key]['price']=floatval($val['price']);                       //单价
            $xyInf[$key]['allPrice']=$val['price']*floatval($val['yp_total_amount']);      //总金额
        }
        $this->ajaxReturn($xyInf);
    }
//    点击预审直接显示最新的处方信息
    public function ys2(){
        session('cz',1);      //健康档案处置判断取值位置（1处方0bqxx）
        $xy=M("drug_dict");
        $bianmas = I("post.cfids");
        $shuliangs=I("post.shuliang");
        $bianmaArr = explode(',',$bianmas);     //药品编码数组
        $shuliangArr = explode(',',$shuliangs);  //药品数量数组
        $num=count($bianmaArr)-1;  //循环次数
        $xyInf=array();
        for($i = 0; $i < $num; $i++){
            $condition['drug_code']=$bianmaArr[$i];
            $xyDrug=$xy->where($condition)->find();
            //组合成新数组
            $xyInf[$i]['yp_name']=$xyDrug['drug_name'];                             //药品名称
            $xyInf[$i]['num']=floatval($shuliangArr[$i]);                           //数量
            $xyInf[$i]['dw']=substr(trim($xyDrug['drug_spec']),-3);                 //单位
            $xyInf[$i]['js']=1;                                                     //剂数
            $xyInf[$i]['price']=floatval($xyDrug['price']);                         //单价
            $xyInf[$i]['allPrice']=$xyDrug['price']*floatval($shuliangArr[$i]);     //总金额
        }
        $this->ajaxReturn($xyInf);
    }
}
