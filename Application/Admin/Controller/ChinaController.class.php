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
      // dump($_SESSION);die;
        $param = I('get.');
        $where = array();
        $where['department'] = $_SESSION['uid'];//这行以后再解开注释
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

    $dict12 = M('dict_drug_zy');
     $where1['drug_code'] = array('like','A%');
        // $where1['drug_indicator'] = 2;
        $aa = $dict12->field('drug_code')->order('drug_code desc')->find();
//      dump($aa);die;
        $bb = preg_replace('/\D/s', '', $aa['drug_code']);
        $newId = $bb+1;
        $new = 'A'.$newId;
      /*@YXY
       * 显示性味归经
       * ***/
      $model = M('YMakenature');
      $gjml = $model->query("select code,tree,convert(varchar(max),DECRYPTBYKEY(name)) as name  from y_makenature where tree like '0000.003%' AND isshow=0
and len(tree)=12 ORDER BY tree");
      $gjxj = $model->query("select tree,convert(varchar(max),DECRYPTBYKEY(name)) as name  from y_makenature where tree like '0000.003.001%'AND isshow=0 and len(tree)=16 ORDER BY tree");
//      dump($gjml);die;
      $this->assign('newId',$new);
      $this->assign("gjml", $gjml);
      $this->assign("gjxj", $gjxj);
      $this->display('Shuju/addZy');
  }

  public function doAdd(){
    $ly = $_POST['ly'];
    $xz = $_POST['xz'];
    $pz = $_POST['pz'];
    $xw = $_POST['xw'];
    $gj = $_POST['gj'];
    $gjdl = $_POST['gjdl'];
//      dump($gj);die;
    $gn = $_POST['gn'];
    $zz = $_POST['zz'];
    $yfyl = $_POST['yfyl'];
    $tsyf = $_POST['tsyf'];
    $zc = $_POST['zc'];
//    $wrph = $_POST['wrph'];
    $key = 'TcmSQL2005Key';
        /*
         *@杨旭亚
         *2017-06-08
         * 1.判断区分温热平寒
         * 2.判断区分药物归经
         */
        //判断是否需要添加温热平寒
      $makdict = M('y_makenature');
      if(!empty($xw)){
          $zushuzuwrph = explode("|",$xw);
          $wrphhz =$zushuzuwrph['1'];//前台输入的温热平寒
          //判断将温热平寒转换为对应数字
          if($wrphhz =='大寒'){
              $wrph = '001';
          }elseif($wrphhz =='微寒'){
              $wrph = '002';
          }elseif($wrphhz =='寒'){
              $wrph = '003';
          }elseif($wrphhz =='大热'){
              $wrph = '004';
          }elseif($wrphhz =='热'){
              $wrph = '005';
          }elseif($wrphhz =='微温'){
              $wrph = '006';
          }elseif($wrphhz =='温'){
              $wrph = '007';
          }elseif($wrphhz =='平'){
              $wrph = '008';
          }elseif($wrphhz =='凉'){
              $wrph = '009';
          }
          //去y_make表搞事情
          $beg = '0000.002.'.$wrph;

          // $find['tree'] = array('like',"0000.002.001%");
          $mas = $makdict->query("select tree from y_makenature where tree like '$beg%' order by tree desc");
          // dump($mas);die;
          $mast = $mas[0]['tree'];
          $ar = explode('.',$mast);
          $tt = $ar[3]+1;
          $newTree = $beg.'.'.$tt;
          $dataa['CODE'] = $_POST['drug_code'];
          $dataa['TREE'] = $newTree;
          $dataa['NAME'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$_POST[name]'))");
          $dataa['isshow'] = 1;
          // dump($dataa);die;
          $result = $makdict->add($dataa);
      }
      //2.判断是否添加药物归经
      if(!empty($gj)){
          //查出该归经下的药品区最大值
              $maszilei = $makdict->query("select tree from y_makenature where tree like '$gj%' ORDER BY tree DESC");
              $ar = explode('.',$maszilei[0][tree]);
              $tt = $ar[4]+1;
//          拼接最终tree
              $newTree =  $ar[0].'.'.$ar[1].'.'.$ar[2].'.'.$ar[3].'.'.$tt;
              $dataa['CODE'] = $_POST['drug_code'];
              $dataa['TREE'] = $newTree;
              $dataa['NAME'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$_POST[name]'))");
              $dataa['isshow'] = 1;
//               dump($dataa);die;
              $result = $makdict->add($dataa);
      }else{
          //判断大类里是否有值
          if(!empty($gjdl)){
              //查出该归经下的药品区最大值
              $maszilei = $makdict->query("select tree from y_makenature where tree like '$gjdl%'and isshow=1 ORDER BY tree DESC");
              $ar = explode('.',$maszilei[0][tree]);
              //判断子类下是否有值
              if(isset($maszilei)){
                  $newTree =  $gjdl.'.001';
              }else{
                  $tt = $ar[3]+1;
                // 拼接最终tree
                  $newTree =  $ar[0].'.'.$ar[1].'.'.$ar[2].'.'.$tt;
              }

//              dump($maszilei);dump($ar);dump($tt);die;
              $dataa['CODE'] = $_POST['drug_code'];
              $dataa['TREE'] = $newTree;
              $dataa['NAME'] = array('exp',"CONVERT(varbinary(max),ENCRYPTBYKEY(Key_GUID('$key'),'$_POST[name]'))");
              $dataa['isshow'] = 1;
//               dump($dataa);die;
              $result = $makdict->add($dataa);
          }
      }
    $dict2 = M('dict_drug_zy_mx');
      $data['drug_name'] = $_POST['name'];
      $data['other_name'] = $_POST['other'];
      $data['price'] = $_POST['price'];
      $data['input_code'] = $_POST['inputCode'];
      $data['bz'] = $_POST['bz'];
      $data['zysx'] = $_POST['zysx'];
      $data['drug_indicator']=(int)02;
      $data['drug_code'] = $_POST['drug_code'];
      $data['department'] = $_SESSION['did'];
// dump($data);die;
      $datazy['flag'] = I('post.bz');
      $data['drug_units'] = '克';
      $dict = M('dict_drug_zy');
      $res = $dict->add($data);
      $dict1 = M('drug_dict');
      $res1 = $dict1->add($data);
      //加入drug_dict
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
      $lis['department'] = $_SESSION['did'];
      $re = $dict2->add($lis);
      if($re){
        echo '一大堆添加成功l！';
      }



  }
  public function delete(){
     $code = $_POST['code'];
     $dict = M('dict_drug_zy');
     $dict1 = M('drug_dict');
     $dict2 = M('dict_drug_zy_mx');
     $dict3 = M('y_makenature');
     $res = $dict->where("drug_code ='$code'")->delete();
     $res1 = $dict1->where("drug_code ='$code'")->delete();
     $res2 = $dict2->where("drug_code ='$code'")->delete();
     $res3 = $dict3->where("CODE = '$code'")->delete();
     if ($res3) {
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
    $data2['name_alias'] = $_POST['other'];
    $data2['price'] = $_POST['price'];
    $where['department'] = session('uid');
    $dict = M('drug_dict');
    $dic = M('dict_drug_zy');
    $res = $dict->where("drug_code='$code'")->where($where)->save($data);
    $ser = $dic->where($where)->where("drug_code='$code'")->save($data2);
    if($ser){
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

    /**
     *性味归经点击子类
     */
    public function huoquzl(){
        $xwgjdl = I('post.xwgjdl');
        $user = M('y_makenature');
        $data = $user->query("select tree,convert(varchar(max),DECRYPTBYKEY(name)) as name  from y_makenature where tree like '$xwgjdl%'AND isshow=0 and len(tree)=16 ORDER BY tree");
        $this->ajaxReturn($data);
    }
}