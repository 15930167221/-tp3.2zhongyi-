<?php
namespace Admin\Controller;

use Admin\Model\PpricElistModel;
use Think\Controller;
use Think\Page;

class ShoufeiController extends AdminController
{
	 protected function _initialize()
    {
        parent::_initialize();
        $this->mod = new PpricElistModel();
    }
    public function index()
    {
        $param = I('get.');
        $where = array();
        //获取机构编码
        $department = session('did');
        $where['department'] = array(array('eq',1),array('eq',$department), 'or') ;
        (!empty($param)) && $where['ITEM_NAME'] = array('like',"%{$param['item_name']}%");
        //总条数
        $count = $this->mod->getCount($where);
        $page = new Page($count, C('PAGE_SIZE'));
        //列表信息
        $res = $this->mod->getListFromPage($where, $page);
        // dump($res);die;
        // echo $this->mod->getLastSql();
        $this->assign('info', array('list' => $res,'count' => $count, 'page' => $page->show()));
        $this->display();
    }
    public function add(){
    	if (IS_POST) {
            //接受条件
            //获取机构编码
            $department = session('did');
            $item_code = I('post.item_code');
            $item_name = I('post.item_name');
            $input_code = I('post.input_code');
            $item_spec = I('post.item_spec');
            $price = I('post.price');
            $units_code = I('post.units_code');
    		$enabl_sign = I('post.enabl_sign');
            //执行添加
    		$User = M("p_price_list"); // 实例化User对象
            // $aa = I('post.');
            // dump($aa);die;
			$data['ITEM_CODE'] = $item_code;
            $data['ITEM_NAME'] = $item_name;
            $data['INPUT_CODE'] = $input_code;
            $data['ITEM_SPEC'] = $item_spec;
            $data['PRICE'] = $price;
            $data['UNITS_CODE'] = $units_code;
			$data['ENABL_SIGN'] = $enabl_sign;
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
    		$item_code = I('post.item_code');
            $item_name = I('post.item_name');
            $input_code = I('post.input_code');
            $item_spec = I('post.item_spec');
            $price = I('post.price');
            $units_code = I('post.units_code');
            $enabl_sign = I('post.enabl_sign');
            //执行添加
            $User = M("p_price_list"); // 实例化User对象
            // $aa = I('post.');
            // dump($aa);die;
            $where['id']= $id;
            $data['ITEM_CODE'] = $item_code;
            $data['ITEM_NAME'] = $item_name;
            $data['INPUT_CODE'] = $input_code;
            $data['ITEM_SPEC'] = $item_spec;
            $data['PRICE'] = $price;
            $data['UNITS_CODE'] = $units_code;
            $data['ENABLED_SIGN'] = $enabl_sign;
			// dump($data);die;
			$res = $User->where($where)->data($data)->save();
        	// echo $User->getLastSql();die;

			$res == true ? $this->success('修改成功') : $this->error('修改失败');
    		// $this->display();
    	}else{
    		$jxdm = I('get.id');
    		// dump($jxdm);die;
    		$user = M('p_price_list');
    		$where['id'] = $jxdm;
    		$data = $user->where($where)->select();
    		// dump($data);die();
    		$this->assign('data',$data);
    		$this->display();
    	}
    }
    public function delete(){
    	$condition = I('post.id');
    	$where['id'] = $condition;
    	$res = M('p_price_list')->where($where)->delete();
    	if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
    }
}