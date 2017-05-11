<?php
namespace Admin\Controller;

use Admin\Model\MembersModel;
use Think\Controller;
use Think\Page;

/**
 * 配伍禁忌十八反
 * Class TabooShibaController
 * @package Admin\Controller
 */
class TabooShibaController extends AdminController
{
    public function index(){
        $jy=M('jy');
        $ypName=I("get.name");
        if($ypName){
            //条目数
            $shibaCount=M()->field("t2.drug_name as yuan,t3.drug_name as fan")->table("jy t1,drug_dict t2,drug_dict t3")->where("(t1.department=".session('did')." or t1.department=0) and t2.department='1' and t3.department='1' and t1.YP1=t2.drug_code and t1.YP2=t3.drug_code and LX='1' and (t2.drug_name='".$ypName."' or t3.drug_name='".$ypName."')")->count();
            //页数
            $page = new Page($shibaCount, C('PAGE_SIZE'));
            //十八反表
            $shiba=M()->field("t2.drug_name as yuan,t3.drug_name as fan,ID")->table("jy t1,drug_dict t2,drug_dict t3")->where("(t1.department=".session('did')." or t1.department=0) and t2.department='1' and t3.department='1' and t1.YP1=t2.drug_code and t1.YP2=t3.drug_code and LX='1' and (t2.drug_name='".$ypName."' or t3.drug_name='".$ypName."')")->order('ID')->limit($page->firstRow, $page->listRows)->select();
        }else{
            //条目数
            $shibaCount=$jy->where("(department=".session('did')." or department=0) and lx='1'")->count();
            //页数
            $page = new Page($shibaCount, C('PAGE_SIZE'));
            //十八反表
            $shiba=M()->field("t2.drug_name as yuan,t3.drug_name as fan,ID")->table("jy t1,drug_dict t2,drug_dict t3")->where("(t1.department=".session('did')." or t1.department=0) and t2.department='1' and t3.department='1' and t1.YP1=t2.drug_code and t1.YP2=t3.drug_code and LX='1'")->order('ID')->limit($page->firstRow, $page->listRows)->select();
        }
        $this->assign('sbList',array('list' => $shiba,'count' => $shibaCount, 'page' => $page->show()));
        $this->display();
    }
    //十八反添加页面
    public function add(){
        $this->display();
    }
    //十八反执行添加
    public function doAdd(){
        $yName=I("post.yName");//药品名称
        $fName=I("post.fName");//反药名称
        $jy=M('jy');
        $dict=M('drug_dict');
        //将名称转为编号
        $yNameCo=$dict->where("drug_name='".$yName."'")->find();
        $fNameCo=$dict->where("drug_name='".$fName."'")->find();
        $condition['YP1']=$yNameCo['drug_code'];//药品名称编号
        $condition['YP2']=$fNameCo['drug_code'];//反药名称编号
        $condition['LX']='1';
        $condition['department']=array(array('eq',session('did')),array('eq','0'),'or');
        //判断是否存在，存在则不能添加，否则进行添加
        $check=$jy->where($condition)->select();
        if($check){
            $this->ajaxReturn(array('status' => false, 'msg' => '该记录已存在，请勿重复添加！!'));
        }else{
            $condition['department']=session('did');
            $res=$jy->add($condition);
            if($res){
                $this->ajaxReturn(array('status' => true, 'msg' => '保存成功!'));
            }else{
                $this->ajaxReturn(array('status' => false, 'msg' => '保存失败!'));
            }
        }
    }
    //十八反药品删除
    public function delete(){
        $condition['ID'] = I('post.id');
        empty($condition['ID']) && $this->error('无效参数');
        $jy=M('jy');
        $res =$jy->where($condition)->delete();
        if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
    }
    //十八反修改页面
    public function edit(){
        $id=I("get.id");
        $res=M()->field("t2.drug_name as yuan,t3.drug_name as fan,ID")->table("jy t1,drug_dict t2,drug_dict t3")->where("t1.YP1=t2.drug_code and t1.YP2=t3.drug_code and LX='1' and ID='".$id."'")->find();
        $this->assign('infList',$res);
        $this->display();
    }
    //十八反执行修改
    public function doEdit(){
        $yName=I("post.yName");//药品名称
        $fName=I("post.fName");//反药名称
        $id=I("post.id");
        $jy=M('jy');
        $dict=M('drug_dict');
        //将名称转为编号
        $yNameCo=$dict->where("drug_name='".$yName."'")->find();
        $fNameCo=$dict->where("drug_name='".$fName."'")->find();
        $condition['YP1']=$yNameCo['drug_code'];//药品名称编号
        $condition['YP2']=$fNameCo['drug_code'];//反药名称编号
        $condition['LX']='1';
        //判断是否存在，存在则不能添加，否则进行添加
        $check=$jy->where($condition)->select();
        if($check){
            $this->ajaxReturn(array('status' => false, 'msg' => '该记录已存在，请返回修改！!'));
        }else{
            $res=$jy->where("ID='".$id."'")->save($condition);
            if($res){
                $this->ajaxReturn(array('status' => true, 'msg' => '保存成功!'));
            }else{
                $this->ajaxReturn(array('status' => false, 'msg' => '保存失败!'));
            }
        }
    }
}