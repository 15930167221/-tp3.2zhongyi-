<?php
namespace Admin\Controller;

use Admin\Model\DrugDictModel;
use Think\Controller;
use Think\Page;

class XiyaoController extends AdminController
{
     protected function _initialize()
    {
        parent::_initialize();
        $this->mod = new DrugDictModel();
    }
    public function index()
    {
        $param = I('get.');
        $where = array();
        //获取机构编码
        $department = session('did');
        // $where['department'] = $department;
        // $where['department'] = 1;
        // $where['_logic'] = 'or';
        $where['department'] = array(array('eq',1),array('eq',$department), 'or') ;
        $where['drug_indicator'] = array('in','1,3');
        (!empty($param)) && $where['drug_name'] = array('like',"%{$param['drug_name']}%");
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
             //获取机构编码
            $department = session('did');
            //获取数据
            $drug_code = I('post.drug_code');
            $input_code = I('post.input_code');
            $drug_name = I('post.drug_name');
            $package_units = I('post.package_units');
            $drug_form = I('post.drug_form');
            $dodrug_indicator = I('post.drug_indicator');//类别
            $drug_indicator = floatval ($dodrug_indicator);//强转成数字
            $dohl = I('post.hl');//含量
            $dohl = floatval ($dohl);//强转成数字
            $hl_unit = I('post.hl_unit');
            $bzdw1 = I('post.bzdw1');
            $doprice = I('post.price');//价格
            $price = floatval ($doprice);//强转成数字
    		$memos = I('post.memos');
    		$User = M("drug_dict"); // 实例化User对象
            // dump($drug_code);die;
            $data['drug_code'] = $drug_code;
            $data['input_code'] = $input_code;
            $data['drug_name'] = $drug_name;
            $data['package_units'] = $package_units;
            $data['drug_form'] = $drug_form;
            $data['drug_indicator'] = $drug_indicator;
            $data['hl'] = $hl;
            $data['hl_unit'] = $hl_unit;
            $data['bzdw1'] = $bzdw1;
            $data['price'] = $price;
            $data['memos'] = $memos;
            $data['department'] = $department;
            // dump($data);die;
			$res = $User->add($data);
			$res == true ? $this->success('添加成功') : $this->error('添加失败');
    		// $this->display();
    	}else{
            //获取包装单位
            $baozhuangUser = M('sys_dm_jldw');
            $baozhuangdata = $baozhuangUser->where('lb=02')->field('dwdm,dw')->select(); 
            // dump($hanliangdata);die;
            $this->assign('baozhuangdata',$baozhuangdata);
            //获取含量单位
            $hanliangUser = M('sys_dm_jldw');
            $hanliangdata = $hanliangUser->where('lb=01')->field('dwdm,dw')->select(); 
            // dump($hanliangdata);die;
            $this->assign('hanliangdata',$hanliangdata);
            //获取剂型
            $jixingUser = M('sys_dm_jx');
            $jixingData = $jixingUser->field('jxdm,jxmc')->select();
            // dump($jixingData);die;
            $this->assign('jixingdata',$jixingData);
            //获取药品代码
            $User = M('drug_dict');
            $where['drug_indicator'] = array('in','1,3');
            $data = $User->where($where)->field('drug_code')->order('drug_code desc')->select();
            // echo $User->getLastSql();die;
            // 判断是否为空
            if (!$data) {
                $dodata = 'G0000001';
            }else{
                //获取最大的
                $aa = $data['0']['drug_code'];
                $doaa = $aa+1;
                $dodata = '00'.$doaa;
            }
            // dump($dodata);die;
            $this->assign('data',$dodata);
    		$this->display();
    	}
    	
    }
    public function edit(){
    	if (IS_POST) {
            //获取数据
            $drug_code = I('post.drug_code');
            $input_code = I('post.input_code');
            $drug_name = I('post.drug_name');
            $package_units = I('post.package_units');
            $drug_form = I('post.drug_form');
            $dodrug_indicator = I('post.drug_indicator');//类别
            $drug_indicator = floatval ($dodrug_indicator);//强转成数字
            $dohl = I('post.hl');//含量
            $dohl = floatval ($dohl);//强转成数字
            $hl_unit = I('post.hl_unit');
            $bzdw1 = I('post.bzdw1');
            $doprice = I('post.price');//价格
            $price = floatval ($doprice);//强转成数字
            $memos = I('post.memos');
            $enable_flag = I('post.enable_flag');//启用标志
            //执行修改
            $User = M("drug_dict"); // 实例化User对象
            // dump($drug_code);die;
            $where['drug_code'] = $drug_code;
            $data['input_code'] = $input_code;
            $data['drug_name'] = $drug_name;
            $data['package_units'] = $package_units;
            $data['drug_form'] = $drug_form;
            $data['drug_indicator'] = $drug_indicator;
            $data['hl'] = $hl;
            $data['hl_unit'] = $hl_unit;
            $data['bzdw1'] = $bzdw1;
            $data['price'] = $price;
            $data['memos'] = $memos;
            $data['enable_flag'] = $enable_flag;
			$res = $User->where($where)->data($data)->save();
        	// echo $User->getLastSql();die;

			$res == true ? $this->success('修改成功') : $this->error('修改失败');
    		// $this->display();
    	}else{
             //获取包装单位
            $baozhuangUser = M('sys_dm_jldw');
            $baozhuangdata = $baozhuangUser->where('lb=02')->field('dwdm,dw')->select(); 
            // dump($hanliangdata);die;
            $this->assign('baozhuangdata',$baozhuangdata);
            //获取含量单位
            $hanliangUser = M('sys_dm_jldw');
            $hanliangdata = $hanliangUser->where('lb=01')->field('dwdm,dw')->select(); 
            // dump($hanliangdata);die;
            $this->assign('hanliangdata',$hanliangdata);
            //获取剂型
            $jixingUser = M('sys_dm_jx');
            $jixingData = $jixingUser->field('jxdm,jxmc')->select();
            // dump($jixingData);die;
            $this->assign('jixingdata',$jixingData);
            //获取更改信息
    		$id = I('get.id');
    		// dump($id);die;
    		$user = M('drug_dict');
    		$where['drug_code'] = $id;
    		$data = $user->where($where)->select();
    		// dump($data);die();
    		$this->assign('data',$data);
    		$this->display();
    	}
    }
    public function delete(){
    	$condition = I('post.id');
    	$where['drug_code'] = $condition;
    	$res = M('drug_dict')->where($where)->delete();
    	if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
    }
}