<?php
namespace Admin\Controller;

use Admin\Model;
use Think\Controller;
use Think\Page;

class YizhuController extends AdminController
{
    public function index()
    {
        $rect = M('dict_order');
        //获取机构编码
        $department = session('did');
        $where['department'] = array(array('eq',1),array('eq',$department), 'or') ;
        $count = $rect->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
        $page = getpage($count,9);//控制页面显示条数
        $show = $page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        //以上是分页 ， 以下是数据
        //'jz_flag=1'
        $data =  $rect->where($where)->limit($page->firstRow.','.$page->listRows)->select();//查询数据（未完成就诊的）$Page->firstRow 起始条数 $Page->listRows 获取多少条
        $this->assign('data',$data);// 赋值模板变量
        $this->display();
    }
    public function add(){
    	if (IS_POST) {
            //获取机构编码
            $department = session('did');
            $id = I('post.order_code');
            $name = I('post.order_name');
    		$input = I('post.input_code');
    		$User = M("dict_order"); // 实例化User对象
            $data['order_code'] = $id;
            $data['order_name'] = $name;
			$data['input_code'] = $input;
            $data['department'] = $department;
			$res = $User->add($data);
			$res == true ? $this->success('添加成功') : $this->error('添加失败');
    		// $this->display();
    	}else{
    		$this->display();
    	}
    	
    }
    public function edit(){
    	if (IS_POST) {
            $id = I('post.id');
    		$order_code = I('post.order_code');
            $name = I('post.order_name');
            $input = I('post.input_code');
    		$User = M("dict_order"); // 实例化User对象
    		$where['id'] = $id;
			$data['order_code'] = $order_code;
            $data['order_name'] = $name;
            $data['input_code'] = $input;
			// dump($data);die;
			$res = $User->where($where)->data($data)->save();
        	// echo $User->getLastSql();die;

			$res == true ? $this->success('修改成功') : $this->error('修改失败');
    		// $this->display();
    	}else{
    		$id = I('get.id');
    		// dump($jxdm);die;
    		$user = M('dict_order');
    		$where['id'] = $id;
    		$data = $user->where($where)->select();
    		// dump($data);die();
    		$this->assign('data',$data);
    		$this->display();
    	}
    }
    public function delete(){
    	$condition = I('post.id');
    	$where['id'] = $condition;
    	$res = M('dict_order')->where($where)->delete();
    	if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
    }
}