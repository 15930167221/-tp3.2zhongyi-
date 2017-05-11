<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;

/**
 * 配伍禁忌孕妇禁忌
 * Class TabooYunfuController
 * @package Admin\Controller
 */
class TabooYunfuController extends AdminController
{
    //孕妇禁忌查询
    public function index(){
        $jbxx=M('jbxx');
        $ypName=I("get.name");
        //是否通过搜索查询
        if($ypName){
            //条目数
            $yfCount=$jbxx->where("(department=".session('did')." or department=0) and xxlbdm='12' and xxmc='".$ypName."'")->count();
            //页数
            $page = new Page($yfCount, C('PAGE_SIZE'));
            //禁忌表
            $yunfu=$jbxx->field("id,xxmc,bz")->where("(department=".session('did')." or department=0) and xxlbdm='12' and xxmc='".$ypName."'")->limit($page->firstRow, $page->listRows)->select();
        }else{
            //条目数
            $yfCount=$jbxx->where("(department=".session('did')." or department=0) and xxlbdm='12'")->count();
            //页数
            $page = new Page($yfCount, C('PAGE_SIZE'));
            //禁忌表
            $yunfu=$jbxx->field("id,xxmc,bz")->where("(department=".session('did')." or department=0) and xxlbdm='12'")->limit($page->firstRow, $page->listRows)->select();
        }
        $this->assign('yfList',array('list' => $yunfu,'count' => $yfCount, 'page' => $page->show()));
        $this->display();
    }
    //孕妇禁忌添加页面
    public function add(){
        $this->display();
    }
    //孕妇禁忌执行添加
    public function doAdd(){
        $val=I("post.");
        $jbxx=M('jbxx');
        $data=array();
        $data['XXDM']=$val['ypCode'];//药品编码
        $data['XXMC']=$val['name'];//药品名称
        $data['INPUTCODE']=$val['inputCode'];//拼音码
        $data['BZ']=$val['bz'];//禁用，慎用
        $data['XXLBDM']='12';
        $data['department']=array(array('eq',session('did')),array('eq','0'),'or');
        //判断是否存在，存在则不能添加，否则进行添加
        $check=$jbxx->where($data)->select();
        if($check){
            $this->ajaxReturn(array('status' => false, 'msg' => '该记录已存在，请勿重复添加！!'));
        }else{
            $data['department']=session('did');
            $res=$jbxx->add($data);
            if($res){
                $this->ajaxReturn(array('status' => true, 'msg' => '保存成功!'));
            }else{
                $this->ajaxReturn(array('status' => false, 'msg' => '保存失败!'));
            }
        }
    }
    //孕妇禁忌药品输入查询
    public function selectAjax(){
        $val=I("post.value");
        $zy=M('drug_dict');
        $res=$zy->field("drug_code,drug_name,RTRIM(input_code) as input_code")->where("(drug_name like '%".$val."%' or input_code like '%".$val."%') and (department=".session('did')." or department=1)")->select();
        $this->ajaxReturn($res);
    }
    //孕妇禁忌药品删除
    public function delete(){
        $condition['id'] = I('post.id');
        empty($condition['id']) && $this->error('无效参数');
        $jbxx=M('jbxx');
        $res =$jbxx->where($condition)->delete();
        if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
    }
    //孕妇禁忌修改页面
    public function edit(){
        $id=I("get.id");
        $jbxx=M("jbxx");
        $res=$jbxx->where("id='".$id."'")->find();
        $this->assign('infList',$res);
        $this->display();
    }
    //孕妇禁忌执行修改
    public function doEdit(){
        $val=I("post.");
        $jbxx=M('jbxx');
        $data=array();
        $data['XXDM']=$val['ypCode'];//药品编码
        $data['XXMC']=$val['name'];//药品名称
        $data['INPUTCODE']=$val['inputCode'];//拼音码
        $data['BZ']=$val['bz'];//禁用，慎用
        //判断是否存在，存在则不能添加，否则进行添加
        $check=$jbxx->where($data)->select();
        if($check){
            $this->ajaxReturn(array('status' => false, 'msg' => '该记录已存在，请返回修改！!'));
        }else{
            $res=$jbxx->where("id='".$val['id']."'")->save($data);
            if($res){
                $this->ajaxReturn(array('status' => true, 'msg' => '保存成功!'));
            }else{
                $this->ajaxReturn(array('status' => false, 'msg' => '保存失败!'));
            }
        }
    }
}