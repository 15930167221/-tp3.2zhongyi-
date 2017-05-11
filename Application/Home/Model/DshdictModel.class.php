<?php
namespace Home\Model;
use Think\Model;
class DshdictModel extends Model{
	public function getinfo($condition){
		$result=$this
		->field('a.presc_no,a.presc_date,a.operator,a.patient_id,a.indicate,a.doctor_id,b.*')
		->alias('a')
		->join("left join station_p as b on a.patient_id=b.br_id")
		->where($condition)->select();
		return $result;
	}
}


?>