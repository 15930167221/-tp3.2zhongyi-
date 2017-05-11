<?php
namespace Admin\Controller;

use Admin\Model\UserInfoDictModel;
use Think\Controller;
use Think\Page;

class PinlvController extends AdminController
{
  public function index(){
        $rect = M('usepl_table');
        $count = $rect->count();// 查询满足要求的总记录数 $map表示查询条件
        $page = getpage($count,10);//控制页面显示条数
        $show = $page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        //以上是分页 ， 以下是数据
        //'jz_flag=1'
        $data =  $rect->field('usep_code,usep_name')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$data);// 赋值模板变量
        $this->display('Shuju/Pinlv');
  }
  public function add(){
      $this->display('Shuju/addPinlv');
  }
  public function doAdd(){
    $dict = M('usepl_table');
    $data['usep_name'] = $_POST['name'];
      $data['usep_way'] = $_POST['name'];
    
      $code = $dict->order("usep_code desc")->Field('usep_code')->find();
      $ncode = (int)$code['useage_code'];
      $nncode = $ncode+1;
      $data['usep_code'] = $nncode;
      $res = $dict->add($data);
      if($res){
        echo '添加成功';
      }else{
        echo '添加失败';
      }
  }
  public function delete(){
     $code = $_POST['code'];
      $dict = M('usepl_table');
      $where['usep_code'] = $code;
      $res = $dict->where($where)->delete();
        if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
  }
  public function edit(){
      $code = $_GET['code'];
      $dict = M('usepl_table');
      $name = $dict->where("usep_code=$code")->find();
      $this->assign('list',$name);
      $this->display('Shuju/editPinlv');
  }
  public function doEdit(){
      $code = $_POST['id'];
      $name = $_POST['name'];
      $dict = M('usepl_table');
      $data['usep_name'] = $_POST['name'];
      $res = $dict->where("usep_code=$code")->save($data);
        if($res){
          echo '修改成功';
        }else{
          echo '修改失败';
        }
  }
}