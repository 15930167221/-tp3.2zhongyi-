<?php
namespace Admin\Controller;

use Admin\Model\MembersModel;
use Think\Controller;
use Think\Page;

/**
 * 配伍禁忌药量禁忌
 * Class TabooYaoliangController
 * @package Admin\Controller
 */
class TabooYaoliangController extends AdminController
{
    //药量超标查询
    public function index(){
        $jbxx=M('jbxx');
        $ypName=I("get.name");
        //是否通过搜索查询
        if($ypName){
            //条目数
            $yfCount=$jbxx->where("(department=".session('did')." or department=0) and xxlbdm='03' and xxmc='".$ypName."'")->count();
            //页数
            $page = new Page($yfCount, C('PAGE_SIZE'));
            //禁忌表
            $yunfu=$jbxx->field("id,xxmc,bz")->where("(department=".session('did')." or department=0) and xxlbdm='03' and xxmc='".$ypName."'")->limit($page->firstRow, $page->listRows)->select();
        }else{
            //条目数
            $yfCount=$jbxx->where("(department=".session('did')." or department=0) and xxlbdm='03'")->count();
            //页数
            $page = new Page($yfCount, C('PAGE_SIZE'));
            //禁忌表
            $yunfu=$jbxx->field("id,xxmc,bz")->where("(department=".session('did')." or department=0) and xxlbdm='03'")->limit($page->firstRow, $page->listRows)->select();
        }

        $this->assign('yfList',array('list' => $yunfu,'count' => $yfCount, 'page' => $page->show()));
        $this->display();
    }
    //药量超标添加页面
    public function add(){
        $this->display();
    }
    //药量超标执行添加
    public function doAdd(){
        $val=I("post.");
        $jbxx=M('jbxx');
        $data=array();
        $data['XXDM']=$val['ypCode'];//药品编码
        $data['XXMC']=$val['name'];//药品名称
        $data['XXLBDM']='03';
        $data['department']=array(array('eq',session('did')),array('eq','0'),'or');
        //判断是否存在，存在则不能添加，否则进行添加
        $check=$jbxx->where($data)->select();
        if($check){
            $this->ajaxReturn(array('status' => false, 'msg' => '该记录已存在，请勿重复添加！!'));
        }else{
            $data['department']=session('did');
            $data['BZ']=$val['bz'];//数量
            $data['INPUTCODE']=$val['inputCode'];//拼音码
            $res=$jbxx->add($data);
            if($res){
                $this->ajaxReturn(array('status' => true, 'msg' => '保存成功!'));
            }else{
                $this->ajaxReturn(array('status' => false, 'msg' => '保存失败!'));
            }
        }
    }
    //药量超标药品删除
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
    //药量超标修改页面
    public function edit(){
        $id=I("get.id");
        $jbxx=M("jbxx");
        $res=$jbxx->where("id='".$id."'")->find();
        $this->assign('infList',$res);
        $this->display();
    }
    //药量超标执行修改
    public function doEdit(){
        $val=I("post.");
        $jbxx=M('jbxx');
        $data=array();
        $data['XXDM']=$val['ypCode'];//药品编码
        $data['XXMC']=$val['name'];//药品名称
        $data['BZ']=$val['bz'];//药量
        //判断是否存在，存在则不能添加，否则进行添加
        $check=$jbxx->where($data)->select();
        if($check){
            $this->ajaxReturn(array('status' => false, 'msg' => '该记录已存在，请返回修改！!'));
        }else{
            $data['INPUTCODE']=$val['inputCode'];//拼音码
            $res=$jbxx->where("id='".$val['id']."'")->save($data);
            if($res){
                $this->ajaxReturn(array('status' => true, 'msg' => '保存成功!'));
            }else{
                $this->ajaxReturn(array('status' => false, 'msg' => '保存失败!'));
            }
        }
    }
}