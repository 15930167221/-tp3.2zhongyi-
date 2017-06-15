<?php
/**
 * IFS SHENHE
 * User: yxy
 * Date: 2017/6/13
 * Time: 11:20
 */
namespace Home\Controller;
use Think\Controller;

class IntfShenFangController extends Controller
{
    /**
     * 审核 （1）用量超标  （2）有毒 （3）18反19畏 （4）孕妇
     */
    public function intfsf()
    {
        $dataypm = array(
            'is_flag'=>'1',//孕妇标志   0 未孕 1 已孕
            'yp_info'=>array(
                '0'=>array(
                    'drug_code'=>'137',//药品代码
                    'drug_name'=>'甘草',//药品名称
                    'drug_amount'=>'4',//药品数量
                ),
                '1'=>array(
                    'drug_code'=>'34',//药品代码
                    'drug_name'=>'水牛角尖',//药品名称
                    'drug_amount'=>'5',//药品数量
                ),
                '2'=>array(
                    'drug_code'=>'271',//药品代码
                    'drug_name'=>'麻黄',//药品名称
                    'drug_amount'=>'50',//药品数量
                ),
                '3'=>array(
                    'drug_code'=>'147',//药品代码
                    'drug_name'=>'枸杞子',//药品名称
                    'drug_amount'=>'60',//药品数量
                ),
                '4'=>array(
                    'drug_code'=>'920',//药品代码
                    'drug_name'=>'信石',//药品名称
                    'drug_amount'=>'60',//药品数量
                ),
                '5'=>array(
                    'drug_code'=>'139',//药品代码
                    'drug_name'=>'甘遂',//药品名称
                    'drug_amount'=>'60',//药品数量
                ),
                '6'=>array(
                    'drug_code'=>'112',//药品代码
                    'drug_name'=>'丁香',//药品名称
                    'drug_amount'=>'60',//药品数量
                ),
                '7'=>array(
                    'drug_code'=>'473',//药品代码
                    'drug_name'=>'郁金',//药品名称
                    'drug_amount'=>'60',//药品数量
                ),
                '8'=>array(
                    'drug_code'=>'6',//药品代码
                    'drug_name'=>'巴豆',//药品名称
                    'drug_amount'=>'60',//药品数量
                ),
                '9'=>array(
                    'drug_code'=>'671',//药品代码
                    'drug_name'=>'西红花',//药品名称
                    'drug_amount'=>'60',//药品数量
                ),
            )
        );
//        $dataypm = array(
//            'is_flag'=>'1',//孕妇标志   0 未孕 1 已孕
//            'yp_info'=>array(
//                '0'=>array(
//                    'drug_code'=>'277',//药品代码
//                    'drug_name'=>'桔梗',//药品名称
//                    'drug_amount'=>'4',//药品数量
//                ),
//                '1'=>array(
//                    'drug_code'=>'417',//药品代码
//                    'drug_name'=>'栀子',//药品名称
//                    'drug_amount'=>'5',//药品数量
//                ),
//                '2'=>array(
//                    'drug_code'=>'46',//药品代码
//                    'drug_name'=>'薄荷',//药品名称
//                    'drug_amount'=>'50',//药品数量
//                ),
//                '3'=>array(
//                    'drug_code'=>'247',//药品代码
//                    'drug_name'=>'连翘',//药品名称
//                    'drug_amount'=>'60',//药品数量
//                ),
//                '4'=>array(
//                    'drug_code'=>'137',//药品代码
//                    'drug_name'=>'甘草',//药品名称
//                    'drug_amount'=>'60',//药品数量
//                )
//            )
//        );
        $ypcode = array();//存放药品code
        $ypname = array();//存放药品name
        $ypamount = array();//存放药品数量
        foreach($dataypm['yp_info'] as $k=>$v){
            array_push($ypcode,$v['drug_code']);//将所有的药品code放到一个数组里
            array_push($ypname,$v['drug_name']);//将所有的药品名称放到一个数组里
            array_push($ypamount,$v['drug_amount']);//将所有的药品数量放到一个数组里
        };
        //判断是否有不符合标准的
        $pdbiaozhun = 0;
        //审核
        //判断是否是孕妇
        if($dataypm['is_flag'] == 1){
            $pdyfdj = '1';
        }else{
            $pdyfdj = '0';
        }
        $this->assign("pdyfdj",$pdyfdj);
        if($dataypm['is_flag'] == 1){
            //孕妇
            $codeList = $ypcode;
            $copycodelista =$ypname;
            for ($i = 0; $i < count($codeList); $i++) {
                $code[$i] = $codeList[$i];
            }
            $whe['XXDM'] = array('in', $code);
            $whe['XXLBDM'] = '12';
            $dict = M('jbxx');
            $women = $dict->where($whe)->select();
            $b = 0;
            for ($a = 0; $a < count($women); $a++) {
                //获取该药品的code
                foreach ($copycodelista as $copyk => $copyv) {
                    if($copyv == $women[$a]['xxmc']){
//                            dump($copyk+1);
                        //插入的数组中
                        $women[$a]['yaopinxh'] = $copyk+1;
                    }
                }
                if ($women[$a]['bz'] == '禁用') {
                    $b++;
                }
            }
            if ($b > 0) {
                $sfyoujinyong = 1;
            } else {
                $sfyoujinyong = 0;
            }
            if(empty($women)){
                $women = array(
                    '0'=>array(
                        'bz'=>'空',
                    ),
                );
            }else{
                $pdbiaozhun++;
            }
            //判断是否有孕妇禁用
            $this->assign("sfyoujinyong",$sfyoujinyong);
            //孕妇禁用的药品
            $this->assign("women",$women);
        }
        //药品用量超标
        $ypCodeList = $ypcode;
        $ypYlList = $ypamount;
        $res = "";
        $jbxx = M('jbxx');//药品用量表
        $clyp = $jbxx->where("xxlbdm='03'")->select(); //规定的超量药品数据
        $xxdm = i_array_column($clyp, 'xxdm');//二维数组转一维数组
        $comm = array_intersect($ypCodeList, $xxdm);//找出交集
        foreach ($comm as $key => $value) {
            $num = array_keys($ypCodeList, $value);//$num为一个数组,确定交集中元素的位置
            $ypYl = $jbxx->where("xxlbdm='03' and xxdm='" . $value . "'")->find();
            //标准
            if ((int) $ypYl['bz'] < (int) $ypYlList[$num[0]]) {
                $res .= "第" . ($num[0] + 1) . "味药【" . $ypYl['xxmc'] . "】的用量超过规定范围（" . $ypYl['bz'] . "克）!!!<br/>";
            }
        }
//        $list['over'] = $res;
        if(empty($res)){
            $res = "无";
        }else{
            $pdbiaozhun++;
        }
        $this->assign("chaobiao",$res);
        //有毒
//        $code = '';
        $code = $ypcode;
        $where['dict_drug_zy_mx.drug_code'] = array('in', $code);
        $dict = M('dict_drug_zy_mx');
        $duxing = $dict->where($where)->where("dict_drug_zy_mx.dx>0")
            ->join('drug_dict on drug_dict.drug_code=dict_drug_zy_mx.drug_code')
            ->field('dict_drug_zy_mx.dx,dict_drug_zy_mx.drug_code as code,drug_dict.drug_name,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(dict_drug_zy_mx.zysx)) as zysx')->distinct('drug_dict.drug_name')->select();
//        echo $dict->getLastSql();
        if(empty($duxing)){
            $duxing = array(
                '0'=>array(
                    'dx'=>'无',
                    'drug_name'=>'无',
                    'zysx'=>'无'
                )
            );
        }else{
            $pdbiaozhun++;
        }
        $this->assign("duxing",$duxing);
        //18反19畏
//        $codeLista = '';
        $codeLista = $ypcode;
        $dict = M('');
        $nn = 0;
//        dump($codeLista);
        //将所有药品code的数组复制
        $copycodelista = $codeLista;
        foreach ($codeLista as $key => $val) {
//            dump($key);
            array_shift($codeLista);
            foreach ($codeLista as $k => $v) {
//                dump($k);
                $sql = "select * from jy where (YP1 = '{$val}' and YP2 = '{$v}') or (YP1='{$v}' and YP2='{$val}') ";
                $data = $dict->query($sql);
                if (count($data) > 0) {
//                    dump($val);
//                    dump($v);
                    //获取该药品的code
                    foreach ($copycodelista as $copyk => $copyv) {
                        if($copyv == $val){
//                            dump($copyk+1);
                            $data[0]['aypcode'] = $copyk+1;
                        }
                        if($copyv == $v){
//                            dump($copyk+1);
                            $data[0]['bypcode'] = $copyk+1;
                        }
                    }
                    $ret[$nn] = $data[0];
                    $nn++;
                }
            }

        }
        if($ret == null){
            $ret = array(
                '0'=>array(
                    'lx'=>'50',
                )
            );
        }else{
            $pdbiaozhun++;
        }
        $this->assign("fanwei",$ret);
//        $list['fw'] = $ret;
//        dump($list);die;
        //判断是否又不符合指标的
        $this->assign("pdbiaozhun",$pdbiaozhun);
//        dump(json_encode($dataypm));die;
        //药品名称和数量
        $this->assign("dataypm",$dataypm);
        $this->display();
    }
}