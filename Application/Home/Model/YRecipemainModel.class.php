<?php
namespace Home\Model;
use Think\Model;
class YRecipemainModel extends Model{
	protected $truetablename='Y_RECIPEMAIN';
	public function getInfoCode(array $condition){
		return $this->where($condition)
		->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as name,CODE,SPELL,TREE,EXPLAIN,TYPE,SOURCE,EFFICACY,MAINCURE,TSYF")
		->order('type asc,tree')
		->select();
	}
	public function getMin(array $condition){
		return $this->where($condition)
		->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as name,tree,type,spell")
		->select();
	}
	public function getInfoa(array $where){
		 return $this->where($where)->field('TREE,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME')->select();
	}
	public function getNew($cf){
		$dpment = session('dpment');
		$where['TREE'] = $cf;
		return $this->where($where)->where("drug_dict.department=$dpment")->join("bz_cf on bz_cf.CFDM='$cf'")->join("drug_dict on drug_dict.drug_code=bz_cf.YPDM")->field('bz_cf.YPDM,bz_cf.serial_no,bz_cf.yf,drug_dict.drug_name,drug_dict.price,drug_dict.drug_code,bz_cf.sl,drug_dict.xw1,bz_cf.dw,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as cf_name')->select();
	}
	// public function getNew($cf){
	// 	return $this->where("TREE='$cf'")->join("bz_cf on bz_cf.CFDM='$cf'")->join("drug_dict on drug_dict.drug_code=bz_cf.YPDM")->field('bz_cf.YPDM,drug_dict.drug_name,drug_dict.drug_code,bz_cf.sl,drug_dict.xw1,bz_cf.dw,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as cf_name')->select();
	// }
	public function getJdf($like){
		$sql1=M()->field("t2.tree,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(t2.NAME)) as t2name,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(t1.NAME)) as t1name")
				   ->table('Y_RECIPEMAIN as t1,Y_RECIPEMAIN as t2')
				   ->where("t1.name=t2.name")
				   ->min(t2.tree);
		$sql2=M('bz_cf')->field('cfdm')->select(false);
		return $this->field("tree,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as name,type,explain,EFFICACY,MAINCURE,ICD_CODE,spell")
			->where("type='1' and tree in '$sql2' and $like")
			->order("type,tree")
			->select();
	}

	public function getMinTreeByName(array $condition)
    {
        $res = $this->where($condition)->field('min(tree) as tree')->find();
        //echo $this->getLastSql();
        return $res;
    }

    public function getFangJieByTree(array $condition)
    {
        $res = $this->where($condition)
            ->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,EXPLAIN,SOURCE,EFFICACY,MAINCURE,TSYF")
            ->find();
        return $res;
    }
}
?>