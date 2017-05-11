<?php
namespace Admin\Controller;

use Admin\Model\UserInfoDictModel;
use Think\Controller;
use Think\Page;

class TujingController extends AdminController
{
   public function index(){
       $rect = M('useage_table');
        $count = $rect->count();// 查询满足要求的总记录数 $map表示查询条件
        $page = getpage($count,10);//控制页面显示条数
        $show = $page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        //以上是分页 ， 以下是数据
        //'jz_flag=1'
        $data =  $rect->field('useage_code,useage_name')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$data);// 赋值模板变量
        $this->display('Shuju/tujing');
   }
   public function add(){
      $this->display('Shuju/addTujing');
   }
   public function doAdd(){
      $data['useage_name'] = $_POST['name'];
      $data['useage_way'] = $_POST['name'];
      $dict = M('useage_table');
      $code = $dict->order("useage_code desc")->Field('useage_code')->find();
      $ncode = (int)$code['useage_code'];
      $nncode = $ncode+1;
      $data['useage_code'] = $nncode;
      $data['LX'] = (string)$nncode;
      $res = $dict->add($data);
      if($res){
        echo '添加成功';
      }else{
        echo '添加失败';
      }
   }
   public function delete(){
      $code = $_POST['code'];
      $dict = M('useage_table');
      $where['useage_code'] = $code;
      $res = $dict->where($where)->delete();
        if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
   }
   public function edit(){
      $code = $_GET['code'];
      $dict = M('useage_table');
      $name = $dict->where("useage_code=$code")->find();
      $this->assign('name',$name);
      $this->display('Shuju/editTujing');
   }
   public function doEdit(){
      $code = $_POST['code'];
      $name = $_POST['name'];
      $dict = M('useage_table');
      $data['useage_name'] = $_POST['name'];
      $data['useage_way'] = $_POST['name'];
      $res = $dict->where("useage_code=$code")->save($data);
        if($res){
          echo '修改成功';
        }else{
          echo '修改失败';
        }
   }
}