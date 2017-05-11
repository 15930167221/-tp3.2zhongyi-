<?php
namespace Home\Model;
use Think\Model;
class DrugDictModel extends Model{
    protected $tableName = 'drug_dict';

	public function getCode(array $condition){
		return $this->where($condition)->select();	 
	}
}
