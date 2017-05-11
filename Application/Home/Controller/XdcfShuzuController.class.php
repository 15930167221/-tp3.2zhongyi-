<?php
namespace Home\Controller;
use Think\Controller;
class XdcfShuzuController extends Controller {
	public function charuzhubiao(){
        // dump($_GET);die;
        $cf = $_GET[pid];
        $dict = D('bz_cf');
        $cf_tree = explode(' ',$cf);
        array_pop($cf_tree);
        array_shift($cf_tree);
        // dump($cf_tree);die;
        $num = count($cf_tree);
        for($i = 0;$i < $num; $i++){
            $where['bz_cf.cfdm'] = $cf_tree[$i];
            $data[$i] = $dict->Meihb($where,$cf_tree[$i]);
        }
        dump($data);die;
        //拼接数组
        $pinjie = array();
        
	}
}