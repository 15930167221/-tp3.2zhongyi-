<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 11:08
 */
namespace Home\Controller;
use Think\Controller;

class ApiController extends Controller
{
    public function api()
    {
        $data = array(
            'type' => 1, //1,2,3,4,5,6
            'item' => 1, //1,2,3,4,5,6
            'info' => 1, //1,2,3,4,5,6
            'yp_info' => array (
                '0' => array(
                    'yp_code' => '',
                    'yp_name' => ''
                ),
                '1' => array(
                    'yp_code' => '',
                    'yp_name' => ''
                ),
                '2' => array(
                    'yp_code' => '',
                    'yp_name' => ''
                )
            )
        );
//        echo json_encode($data);
        $type = 1;

        switch ($type) {
            //开方
            case 1:
                $this->display('Kaifang/zyUp');
                break;
            //查询药解
            case 2:
//                $yp_code = $data['item'];
                $where['z.drug_code'] = '345';
                $res = D('dict_drug_zy')->getYaoJieByCode($where);
                $res['img'] = "/Public/DrugJpg/".$where['z.drug_code'].".jpg";
                $this->assign('info', $res);
                $this->display('Api/yaojie');
                break;
            //查询方解
            case 3:
                $cf_name = $data['item'];
                $where['convert(varchar(max),DECRYPTBYKEY(NAME))'] = '杏苏散';
                $data = D('y_recipemain')->getMinTreeByName($where);

                $condition['tree'] = $data['tree'];
                $res = D('y_recipemain')->getFangJieByTree($condition);
                $this->assign('info', $res);
                $this->display('Api/fangjie');
                break;
            //经典处方
            case 4:
                $this->jingDian();
                break;
            //用药对比
            case 5:
                $this->display('Api/yyduibi');
                break;
            //知识库
            case 6:
//                $zs_name = data['item'];
                $zs_name = '黄帝内经';
                switch ($zs_name) {
                    case '伤寒论':
                        $this->display('Book/shlx');
                        break;
                    case '黄帝内经':
                        $this->display('Book/hdnj');
                        break;
                    case '金匮要略':
                        $this->display('Book/jgyl');
                        break;
                    case '温病条辨':
                        $this->display('Book/wbtb');
                        break;
                }
                break;
        }
    }

    /*******经典方开始********/
    public function jingDian(){
        $model=M("y_recipemain");
        /*$cons=$model->field('CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME, tree,spell')
                    ->where("TYPE=1 and spell!='null'")
                    ->order('tree asc')
                    ->select();*/
        $sql="select tree,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as name,type,explain,EFFICACY,MAINCURE,ICD_CODE,spell from Y_RECIPEMAIN A where type='1' AND TREE IN(SELECT MIN(TREE) FROM Y_RECIPEMAIN B WHERE B.NAME=A.NAME) and tree in(select cfdm from bz_cf) order by type,tree";
        $cons=$model->query($sql);
        $this->assign('cons',$cons);
        $this->display('Api/jingdian');
    }
    function impidajax(){
        if(preg_match("/^[a-z]/i", $_POST['pym'])){
            $sql="select tree,name,type,explain,EFFICACY,MAINCURE,ICD_CODE,spell from V_RECIPEMAIN where spell like '%".$_POST[pym]."%' order by type,tree";
        }else{
            $sql="select tree,name,type,explain,EFFICACY,MAINCURE,ICD_CODE,spell from V_RECIPEMAIN where name like '%".$_POST[pym]."%' order by type,tree";
        }

        $fjm=M('y_recipemain')->query($sql);
        $this->ajaxReturn($fjm);
    }
    function fangjie(){
        $model=M('bz_cf');
        $result=$model->field('bz_cf.*,b2.drug_name')
            ->join('bz_cf left join dict_drug_zy as b2 on b2.drug_code=bz_cf.ypdm')
            ->where("bz_cf.cfdm='$_POST[tree]'")
            ->order('serial_no asc')
            ->select();
        foreach($result as $k=>$v){
            if($result[$k][yf]==null){
                $result[$k][yf]='';
            }else{
                $cnt=$result[$k][yf];
                $mcn=M('dict_usage')->where("code='$cnt'")->find();
                $result[$k][yf]=$mcn[name];
            }
        }
        $this->ajaxReturn($result);
    }
    function fjcon(){
        //$data=D('YRecipemain')->getInfoCode("tree='$_POST[tree]'"); //别删,还有用！
        $data= M('y_recipemain')
            ->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,CODE,SPELL,TREE,EXPLAIN,SOURCE,EFFICACY,MAINCURE,TSYF")
            ->where("tree='$_POST[tree]'")
            ->select();
        $this->ajaxReturn($data);
    }
    /*******经典方结束********/

    public function jingYanajax(){
        $str = I('get.p');

        $drug_dict = M('v_dict_drug_zy');
        $jyjg = $drug_dict -> field("name,xw,other_name") -> where("input_code like '".$str."%' ") -> select();

//        $a = array(
//            'result' => array(array(123,'name'),array(23,'name2'),array(33,'name3'))
//        );
        $b = array('result'=>$jyjg);
        //$this->ajaxreturn($res);
        $this->ajaxReturn($b,'jsonp') ;
    }

}