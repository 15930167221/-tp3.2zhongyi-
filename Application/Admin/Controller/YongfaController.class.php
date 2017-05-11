<?php
namespace Admin\Controller;

use Admin\Model\UserInfoDictModel;
use Think\Controller;
use Think\Page;

class YongfaController extends AdminController
{
   public function index(){
    // $dict = M('dict_usage');
    // $list = $dict->select();
    // $this->assign('list',$list);
    // $this->display('Yongfa/index');
     // 法一自己写的附带样式
        $rect = M('dict_usage');
        $count = $rect->count();// 查询满足要求的总记录数 $map表示查询条件
        $page = getpage($count,10);//控制页面显示条数
        $show = $page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        //以上是分页 ， 以下是数据
        //'jz_flag=1'
        $data =  $rect->limit($page->firstRow.','.$page->listRows)->select();//查询数据（未完成就诊的）$Page->firstRow 起始条数 $Page->listRows 获取多少条
        // dump($data);die;
       
        // var_dump($data);
        $this->assign('list',$data);// 赋值模板变量
        $this->display();
   }
   public function delete(){

       $id = $_POST['id'];
       $dict = M('dict_usage');
       $where['id'] = $id;
       $res = $dict->where($where)->delete();
        if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
   }
   public function add(){
   		$this->display('Yongfa/add');
   }
   public function doAdd(){
   		$data['code'] = $_POST['code'];
   		$data['name'] = $_POST['name'];
   		$data['input_code'] = $_POST['input_code'];
   		$dict = M('dict_usage');
   		$res = $dict->add($data);
   		if($res){
   			echo '添加成功';
   		}else{
   			echo '添加失败';
   		}
   }
   public function edit(){
    $id = $_GET['code'];
    $dict = M('dict_usage');
      $name = $dict->where("id=$id")->find();
      $this->assign('list',$name);
      $this->display('Shuju/editYongfa');
   }
   public function doEdit(){
      $code = $_POST['code'];
      $name = $_POST['name'];
      $input = $_POST['input_code'];
      $id = $_POST['id'];
      $dict = M('dict_usage');
      $data['name'] = $name;
      $data['code'] = $code;
      $data['input_code'] = $input;
      $res = $dict->where("id=$id")->save($data);
        if($res){
          echo '修改成功';
        }else{
          echo '修改失败';
        }
   }
}