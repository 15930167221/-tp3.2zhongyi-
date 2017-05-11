<?php
namespace Admin\Controller;

use Admin\Model\DrugDictModel;
use Think\Controller;
use Think\Page;

class ChinaController extends AdminController
{
   protected function _initialize()
    {
        parent::_initialize();
        $this->mod = new DrugDictModel();
    }
  public function index(){
        $param = I('get.');
        $where = array();
        $where['department'] = $_SESSION['dpment'];//这行以后再解开注释
        (!empty($param)) && $where['drug_name'] = array('like',"%{$param['drug_name']}%");
        //总条数
        $where['drug_indicator'] = 2;
        //获取最大ID
       

        $count = $this->mod->getCount($where);
        $page = new Page($count, C('PAGE_SIZE'));
        //列表信息
        $res = $this->mod->getListFromPage($where, $page);
        //echo $this->mod->getLastSql();
        $this->assign('info', array('list' => $res,'count' => $count, 'page' => $page->show()));
        $this->display('Shuju/China');
  }
  public function add(){

    $dict12 = M('drug_dict');
     $where1['drug_code'] = array('like','A%');
        $where1['drug_indicator'] = 2;
        $aa = $dict12->where($where1)->field('drug_code')->order('drug_code desc')->find();
        $bb = preg_replace('/\D/s', '', $aa['drug_code']);
        $newId = $bb+1;
        $new = 'A'.$newId;
        $this->assign('newId',$new);
      $this->display('Shuju/addZy');
  }
  public function doAdd(){

    $ly = $_POST['ly'];
    $xz = $_POST['xz'];
    $pz = $_POST['pz'];
    $xw = $_POST['xw'];
    $gj = $_POST['gj'];
    $gn = $_POST['gn'];
    $zz = $_POST['zz'];
    $yfyl = $_POST['yfyl'];
    $tsyf = $_POST['tsyf'];
    $zc = $_POST['zc'];

    $dict2 = M('dict_drug_zy_mx');
      $data['drug_name'] = $_POST['name'];
      $data['other_name'] = $_POST['other'];
      $data['price'] = $_POST['price'];
      $data['input_code'] = $_POST['inputCode'];
      $data['bz'] = $_POST['bz'];
      $data['zysx'] = $_POST['zysx'];
      $data['drug_indicator']=(int)02;
      $data['drug_code'] = $_POST['drug_code'];
      $data['department'] = $_SESSION['dpment'];
      $dict = M('drug_dict');
      $res = $dict->add($data);

      if($res){
          //miracle7kill 同时插入 drug_dict_zy 开方选择药品时用
          $dict_zy = M('dict_drug_zy');
          $datazy['drug_code'] = I('post.drug_code');
          $datazy['drug_name'] = I('post.name');
          $datazy['name_alias'] = I('post.other');
          $datazy['drug_units'] = '克';
          $datazy['price'] = I('post.price');
          $datazy['input_code'] = I('post.inputCode');
          $datazy['flag'] = I('post.bz');
          $datazy['department'] = $_SESSION['dpment'];
          $dict_zy->add($datazy);
          echo '添加成功';
      }else{
        echo '添加失败';
      }
        $key = 'TcmSQL2005Key';
      $lis['drug_code'] = $_POST['drug_code'];
      $lis['ly'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$ly'))");
      $lis['xz'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$xz'))");
      $lis['pz'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$pz'))");
      $lis['xw'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$xw'))");
      $lis['gj'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$gj'))");
      $lis['gn'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$gn'))");
      $lis['zz'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$zz'))");
      $lis['yfyl'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$yfyl'))");
      $lis['tstf'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$tsyf'))");
      $lis['zc'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$zc'))");
      $lis['department'] = $_SESSION['dpment'];
      $re = $dict2->add($lis);
      if($re){
        echo '一大堆添加成功！';
      }
  }
  public function delete(){
     $code = $_POST['code'];
     $dict = M('drug_dict');
     $res = $dict->where("drug_code ='$code'")->delete();
     if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
  }
  public function edit(){
    $code = $_GET['code'];
    $dict = M('drug_dict');
    $list = $dict->where("drug_code='$code'")->find();
    $this->assign('list',$list);
    $this->display('Shuju/editChina');
  }
  public function doEdit(){
    $code = $_POST['id'];
    $data['other_name'] = $_POST['other'];
    $data['price'] = $_POST['price'];
    $dict = M('drug_dict');
    $res = $dict->where("drug_code='$code'")->save($data);
    if($res){
          echo '修改成功';
        }else{
          echo '修改失败';
        }
  }
  public function Yaojie(){
    $str = $_GET['code'];
      $drug_dict = M('drug_dict');
            $zymx = M('dict_drug_zy_mx');
            $zy = M('dict_drug_zy');
            $res = $drug_dict -> where("drug_name = '$str'") -> select();
            $res1 = $zy -> where("drug_name = '$str'") -> select();
            $drug_code = $res[0]['drug_code'];
            $result = $zymx -> field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(pz)) as pz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(xw)) as xw,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(gj)) as gj,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(gn)) as gn,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(zz)) as zz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(yfyl)) as yfyl,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(zysx)) as zysx,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(xz)) as xz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(syz)) as syz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(lcyy)) as lcyy,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(jbyy)) as jbyy,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(xdyj)) as xdyj,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(cydbf)) as cydbf,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(zc)) as zc,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(tstf)) as tstf,drug_code") -> where("drug_code = '$drug_code'") -> select();
              $result[0]['drug_name'] = $res[0]['drug_name'];
            $result[0]['input_code'] = $res[0]['input_code'];
            $result[0]['other_name'] = $res[0]['other_name'];
            $result[0]['source'] = $res[0]['source'];
            $result[0]['price'] = $res1[0]['price'];
            $result[0]['drug_units'] = $res1[0]['drug_units'];
            $result[0]['syz'] = str_replace('|', '&#10;',$result[0]['syz']);
            $result[0]['lcyy'] = str_replace('|', '&#10;',$result[0]['lcyy']);
            $result[0]['src'] = "/zysystem/Public/DrugJpg/".$drug_code.".jpg";
            // $list = $zymx-> where("drug_code = '$drug_code'") ->field('syz,lcyy,jbyy,xdyj,cydbf')->select();
            // $result[0]['syz'].=$list[0]['syz'];
            // $result[0]['lcyy'].=$list[0]['lcyy'];
            // $result[0]['jbyy'].=$list[0]['jbyy'];
            // $result[0]['xdyj'].=$list[0]['xdyj'];
            // $result[0]['cydbf'].=$list[0]['cydbf'];
            $this->assign('drug',$result);
            $this->display('Shuju/yaoJieW');  
    }
    public function other(){
      echo $_GET['otn'];
    }
    public function editYj(){
       $where['drug_code'] = $_POST['hidecode'];
       $dict = M('dict_drug_zy_mx');
       $n = $_POST['syz'];
       $a = $_POST['lcyy'];
       $b = $_POST['jbyy'];
       $c = $_POST['xdyj'];
       $d = $_POST['cydbf'];

       $key = 'TcmSQL2005Key';
       $data['syz'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$n'))");
        $data['lcyy'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$a'))");
         $data['jbyy'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$b'))");
          $data['xdyj'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$c'))");
           $data['cydbf'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$d'))");

       $res = $dict->where($where)->save($data);
       if($res){
          echo '修改成功';
       }else{
          echo '修改失败';
       }
      
    }
    public function spellCode(){
      $sp = $_POST[val];

       $spell=M('dict_hzpy');
        $split=str_split($sp,3);
        foreach ($split as $v){
            $con[]=$spell->where("BHZ='$v'")->find();
        }
        foreach ($con as $v){
            $pym[]=$v['bsm'];
        }
        $pym=implode('',$pym);


      $this->ajaxReturn($pym);
    }
}