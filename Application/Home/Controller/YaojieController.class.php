<?php
namespace Home\Controller;

class YaojieController extends PublicController {

    protected function __initialize()
    {
        parent::_initialize();
    }

    public function index(){
        if($_POST){
            //$path = $_SERVER['DOCUMENT_ROOT'];
            $path = str_replace('\\', '/', realpath(dirname(__FILE__).'/../../../'));
            $filename = substr(strrchr($path, "/"), 0);
            $str = $_POST['str'];
            $drug_dict = M('drug_dict');
            $zymx = M('dict_drug_zy_mx');
            $zy = M('dict_drug_zy');
            $res = $drug_dict -> where("drug_name = '$str'") -> select();
            $res1 = $zy -> where("drug_name = '$str'") -> select();
            $drug_code = $res[0]['drug_code'];
            $result = $zymx -> field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(pz)) as pz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(xw)) as xw,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(gj)) as gj,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(gn)) as gn,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(zz)) as zz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(yfyl)) as yfyl,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(zysx)) as zysx,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(xz)) as xz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(syz)) as syz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(lcyy)) as lcyy,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(jbyy)) as jbyy,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(xdyj)) as xdyj,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(cydbf)) as cydbf,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(zc)) as zc,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(tstf)) as tstf") -> where("drug_code = '$drug_code'") -> select();
            $result[0]['drug_name'] = $res[0]['drug_name'];
            $result[0]['input_code'] = $res[0]['input_code'];
            $result[0]['other_name'] = $res[0]['other_name'];
            $result[0]['source'] = $res[0]['source'];
            $result[0]['price'] = $res1[0]['price'];
            $result[0]['drug_units'] = $res1[0]['drug_units'];
            $result[0]['syz'] = str_replace('|', '<br>&nbsp;&nbsp;&nbsp;&nbsp;',$result[0]['syz']);
            $result[0]['lcyy'] = str_replace('|', '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$result[0]['lcyy']);
            $result[0]['src'] = $filename."/Public/DrugJpg/".$drug_code.".jpg";
            $this -> ajaxReturn($result,'json');
        }else{
            $this -> display();
        }
    }
}