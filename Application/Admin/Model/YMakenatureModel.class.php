<?php
namespace Admin\Model;
use Think\Model;
class YMakenatureModel extends Model{
	function getAll(array $condition){
		return $this->field("code,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,TREE,isshow")
		->where($condition)->order('tree')->select();
	}
	function getAll2(array $condition){
		$where['department'] = session('dpment');
		$list = $this->where($where)->join('drug_dict on drug_dict.drug_code = y_makenature.code')->field("y_makenature.code,drug_dict.drug_name as name,y_makenature.isshow,y_makenature.tree")
		->where($condition)->order('tree')->select();
		return $list;
	}
}
?>