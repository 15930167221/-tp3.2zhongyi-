<?php
namespace Home\Model;

use Think\Model;

class PrescriptionModel extends Model
{
    protected $tableName = 'Prescription';
    public function showZy(array $whe){
        // $cfdict = M('prescription');
        
        $nalist = $this->where($whe)->order('presc_no desc')->field('presc_name,presc_no,indicate,cf_tree')->select();
        return $nalist;
    }
}