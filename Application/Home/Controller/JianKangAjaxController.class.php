<?php
namespace Home\Controller;

class JianKangAjaxController extends PublicController {

    protected function __initialize()
    {
        parent::_initialize();
    }
//    既往史
      public function jws(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=1 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=1')->field('name')->select();
            $this->ajaxReturn($list);
        }
      }
//    传染病史
    public function crbs(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=2 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=2')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    过敏史
    public function gms(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=3 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=3')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    忘神
    public function wangshen(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=4 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=4')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    忘色
    public function wangse(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=5 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=5')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    体态
    public function titai(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=6 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=6')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    体形
    public function tixing(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=7 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=7')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    睡眠质量
    public function shuimianzl(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=8 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=8')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    睡眠时间
    public function shuimiansj(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=9 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=9')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//食欲
    public function shiyu(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=10 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=10')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//口味
    public function kouwei(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=11 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=11')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    大便便次
    public function dbbc(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=12 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=12')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    大便便质
    public function dbbz(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=13 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=13')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    小便便次
    public function xbbc(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=14 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=14')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    小便便色
    public function xbbs(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=15 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=15')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    性情
    public function xingqing(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=16 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=16')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    性格
    public function xingge(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=17 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=17')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    舌色
    public function shezhen(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=18 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=18')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    舌体
    public function sheti(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=19 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=19')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    动态
    public function dongtai(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=20 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=20')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    苔质
    public function taizhi(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=21 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=21')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    苔色
    public function taise(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=22 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=22')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//    脉诊
    public function maizhen(){
        $val=I('post.value');
        $jkda=M("jkda_bl");
        if($val){
            $list=$jkda->where("typeId=23 and (name like '%".$val."%' or pym like '%".$val."%')")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->where('typeId=23')->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
//中医诊断
    public function zhongyizd(){
        $val=I('post.value');
        $jkda=M("tcd_zybm");
        if($val){
            $list=$jkda->distinct(true)->where("NAME like '%".$val."%' or BM_INPUT like '%".$val."%'")->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,BM_INPUT")->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->field('CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME')->select();
            $this->ajaxReturn($list);
        }
    }
//西医诊断
    public function xiyizd(){
        $val=I('post.value');
        $jkda=M("xy_name");
        if($val){
            $list=$jkda->where("name like '%".$val."%' or spell like '%".$val."%'")->field('name')->select();
            $this->ajaxReturn($list);
        }else{
            $list=$jkda->field('name')->select();
            $this->ajaxReturn($list);
        }
    }
    //健康档案中医病名
    public function zybm(){
        $zy=D('tcd_zybm');
        $zyName=$zy->getZyBm();
        $this->ajaxReturn($zyName);
    }
    //健康档案西医病名
    public function xybm(){
        $xy=D('xy_name');
        $xyName=$xy->getXyName();
        $this->ajaxReturn($xyName);
    }
    //医嘱信息修改后保存
    public function yzChange(){

    }
    //获取状态
    public function huoquzhuangtai(){
        $br_id = session('id');
        $xh = session('xh');
        $dpment = session('dpment');
        $user = M('Prescription');
        //拼接公共where
        $where['patient_id'] =  $br_id;
        $where['xh'] =  $xh;
        $where['department'] =  $dpment;
        $datayishen = $user->where($where)->field('indicate')->select();
        $this->ajaxReturn($datayishen);
    }
    //完成就诊
    public function wcjz(){
        $br_id = session('id');
        $xh = session('xh');
        //销毁session
        session('id',null);
        session('xh',null);
        session('tzbsInfo',null);
        session('jkdaSave',null);//判断是否保存过健康档案
        session('save',null);    //判断是否保存过体质辨识
        //将station_p表的jz_flag字段值改为2
        $sta = M('station_p');
        $sta -> where("br_id = '$br_id' and xh = '$xh'") -> setField('jz_flag','2');
        if($sta){
            $a = 1;
        }else{
            $a = 0;
        }
        $this -> ajaxReturn($a,'json');
    }
//    健康档案查看处方打开(历史处方打开)
    public function ckcfAjax(){
        $brid=session('id');
        $xh=I('post.xh');
        $pre=M('Prescription');
        $zyWhere['patient_id']=$brid;
        $zyWhere['xh']=$xh;
        $zyWhere['indicate']=array(array('eq','1'),array('eq','2'),'or');
        $zy=$pre->field('presc_no,dose,presc_name')->where($zyWhere)->select();
        $this->ajaxReturn($zy);
    }
    //点击查看处方处方号查看处方详细信息
    public function ckcfInf(){
        $cfType=I("post.cfType");    //1中药处方，2西药处方
        $cfNum=I("post.cfNum");
        $pred=M('prescription_detail');
        $xydrug=M('xydrugcf_detial');
        if($cfType=="1"){            //中药
            $condition['presc_no']=$cfNum;
            $zy=$pred->where($condition)->order('drug_no')->select();
            $this->ajaxReturn($zy);
        }else if($cfType=="2"){     //西药
            $condition['cf_id']=$cfNum;
            $xy=$xydrug->where($condition)->select();
            $this->ajaxReturn($xy);
        }
    }
    //查看处方中的处方类别选择
    public function cfType(){
        $brid=session('id');
        $xh=I('post.xh');
        $cfType=I("post.cfType");    //1中药处方，2西药处方
        $pre=M('prescription');
        $xydrug=M('xydrugcf_detial');
        if($cfType=="1"){            //中药
            $zyWhere['patient_id']=$brid;
            $zyWhere['xh']=$xh;
            $zyWhere['indicate']=array(array('eq','1'),array('eq','2'),'or');
            $zy=$pre->field('presc_no,dose,presc_name')->where($zyWhere)->select();
            $this->ajaxReturn($zy);
        }else if($cfType=="2"){     //西药
            $xycondition['BR_ID']=$brid;
            $xycondition['XH']=$xh;
            $xycondition['cf_flag']=array(array('eq','1'),array('eq','2'),'or');
            $xy=$xydrug->field("cf_id,yp_name")->where($xycondition)->select();
            $this->ajaxReturn($xy);
        }
    }
    //复制处方操作
    public function copyCf(){
        $brid=session('id');
        $xh=session('xh');
        $cfType=I("post.cfType");    //1中药处方，2西药处方
        $pre=M('prescription');
        $pred=M('prescription_detail');
        $xydrug=M('xydrugcf_detial');
        $cfNums=I("post.cfNums");
        $cfNumArr=explode(',',$cfNums);
        array_pop($cfNumArr);  //处方号数组
        $did=session('wh_userId');
        $dname=session('wh_userName');
        if($cfType=="1"){            //中药
            //获取当日处方最大处方号
            $day=session('wh_userId').date('ymd');
            $where['presc_no'] = array('like',"$day%");
            $mustId = $pre->where($where)->order("presc_no desc")->Field('presc_no')->find();
            $newId = $mustId['presc_no']+1;
            if($newId == 1){
                $newId = $day.'00001';
            }
            //获取结束
            foreach($cfNumArr as $key=>$val){
                $condition['presc_no']=$val;
                $oldzy=$pre->where($condition)->find();             //复制的中药
                $oldzy['presc_no']="$newId";                        //处方号
                $oldzy['presc_date']=date('Y-m-d H:i:s');           //时间
                $oldzy['operator']=$dname;                          //操作员
                $oldzy['indicate']='0';
                $oldzy['xh']=$xh;
                $oldzy['doctor_id']=$did;
                $oldzyInf=$pred->where($condition)->select();       //处方信息
                for($i=0;$i<count($oldzyInf);$i++){
                    $oldzyInf[$i]['presc_no']="$newId";
                    $newzyInf=$pred->add($oldzyInf[$i]);
                }
                $newzy=$pre->add($oldzy);
                if($newzy && $newzyInf){
                    $this->ajaxReturn('复制成功！');
                }else{
                    $this->ajaxReturn('复制失败！');
                }
            }
        }else if($cfType=="2"){     //西药
            //获取当日处方最大处方号
            $day=date('Ymd');
            $where['cf_id'] = array('like',"$day%");
            $mustId = $xydrug->where($where)->order("cf_id desc")->Field('cf_id')->find();
            $newId = $mustId['cf_id']+1;
            if($newId == 1){
                $newId = $day.'00001';
            }
            //获取结束
            foreach($cfNumArr as $key=>$val){
                $condition['cf_id']=$val;
                $oldxy=$xydrug->where($condition)->select();      //复制的西药
                for($i=0;$i<count($oldxy);$i++){
                    $oldxy[$i]['cf_id']="$newId";
                    $oldxy[$i]['cf_date']=date('Y-m-d H:i:s');
                    $oldxy[$i]['BR_ID']=$brid;
                    $oldxy[$i]['XH']=$xh;
                    $oldxy[$i]['cf_flag']='0';
                    $oldxy[$i]['doctor_id']=$did;
                    unset($oldxy[$i]['id']);
                    $newxy=$xydrug->add($oldxy[$i]);
                }
                if($newxy){
                    $this->ajaxReturn('复制成功！');
                }else{
                    $this->ajaxReturn('复制失败！');
                }
            }
        }
    }
    //出生日期控制年龄（健康档案）
    public function ageCon(){
        $brid=session('id');
        $xh=session('xh');
        $user=M('station_p');
        $condition['br_id']=$brid;
        $condition['xh']=$xh;
        $date=date('Y-m-d H:i:s');
        $age=$user->field('dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl')->find();
        $this->ajaxReturn($age);
    }
    //看看健康档案保存没
    public function saveOrNot(){
        $bqxx = M('bqxx');
        $where['BR_ID']=session('id');
        $where['XH']=session('xh');
        $list = $bqxx->where($where)->select();
        if($list){
            echo 1;//保存了
        }else{
            echo 2;//没保存
        }
    }
}