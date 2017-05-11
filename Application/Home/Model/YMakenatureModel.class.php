<?php
namespace Home\Model;
use Think\Model;
class YMakenatureModel extends Model{
	function getAll(array $condition){
		return $this->field("code,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,TREE,isshow")
		->where($condition)->order('tree')->select();
	}
}
?>