<?php
namespace Home\Model;

use Think\Model;

class BzCfModel extends Model
{
    protected $tableName = 'bz_cf';

    public function Meihb(array $where,$cf_tree)
    {
    	   $dpment = session('dpment');
    	   $where['drug_dict.department'] = session('dpment');
        return $this->join('dict_drug_zy on dict_drug_zy.drug_code=bz_cf.ypdm')->where($where)->where("dict_drug_zy.department= $dpment")->join('drug_dict on dict_drug_zy.drug_code=drug_dict.drug_code')->join("tcd_zybm on tcd_zybm.cf_tree='$cf_tree'")->field('dict_drug_zy.drug_name,bz_cf.dw,bz_cf.serial_no,bz_cf.yf,dict_drug_zy.drug_code,drug_dict.xw1,drug_dict.price,bz_cf.sl,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(tcd_zybm.cf_name)) as cf_name,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(tcd_zybm.NAME)) as zyname,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(tcd_zybm.ZX)) as zx,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(tcd_zybm.ZF)) as zf')->select();
    }
    


}