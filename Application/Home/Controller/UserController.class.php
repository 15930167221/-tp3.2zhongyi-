<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function del($id){
      $user = M('user_info_dict');
      $user->where("id={$id}")->delete();
      $this->redirect('Login/userManage');
    }
    public function addManage(){
    	$data['userName'] = $_POST['userName'];
    	$data['power'] = $_POST['powerVal'];
    	$data['userPhone'] = $_POST['userPhone'];
    	$data['code'] = $_SESSION['wh_code'];
    	// $data[''] = ;
    	$user = M('user_info_dict');
    	$user->data($data)->add();
      $this->redirect('Login/userManage');
    }
    public function uploadinfo(){
      // dump($_POST);
      $user = M('user_info_dict');
      $data['userName'] = $_POST['userName'];
      $data['power'] = $_POST['powerVal'];
      $data['userPhone'] = $_POST['userPhone'];
      $id = $_POST['userid'];
      $user->where("id=$id")->save($data);
      $this->redirect('Login/userManage');
    }
    
}