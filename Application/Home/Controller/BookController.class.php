<?php
namespace Home\Controller;
use Think\Controller;

class BookController extends Controller {

    public function index(){
        $this->display();
    }
    public function hdnj(){
        $this->display();
    }
    public function jgyl(){
        $this->display();
    }
    public function shlx(){
        $this->display();
    }
    public function wbtb(){
        $this->display();
    }
    //临床诊断
    public function clinical(){

        $user = M('emr_zhenliaofanganunit');
        // 中医
        $where['UnitType'] = 1;
        $data = $user->where($where)->select();
        $this->assign("data",$data);
        // 西医
        $xiyiwhere['UnitType'] = 0;
        $xiyidata = $user->where($xiyiwhere)->select();
        $this->assign("xiyidata",$xiyidata);
        $this->display();
    }
    //ajax 点击出现子类
    public function ajaxzlznzilei(){
        $zhuaxuyhaoid = I('post.zhuaxuyhaoid');
        // $zhuaxuyhaoid = 1;
        $user = M('emr_zhenliaofangan');
        $where['UnitID'] = $zhuaxuyhaoid;
        $data = $user->where($where)->field('id,unitid,jibingmingcheng')->select();
        // dump($data);die;
        $this->ajaxReturn($data);
    }
    // 子类对应的内容
    public function ajaxzilneilong(){
        $tjneirong = I('post.tjneirong');
        $unittjneirong = I('post.unittjneirong');
        //$content = getZhenDuanContent($tjneirong, $unittjneirong);
        $content = getZhenDuanContent($tjneirong,$unittjneirong);
        
        $this->ajaxReturn($content);
    }
}