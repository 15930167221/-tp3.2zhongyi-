<?php
namespace Home\Model;
use Think\Model;
class DictDrugZyMxModel extends Model{
	function AllField( array $condition){
		return $this/*->where($condition)*/
		->field("drug_code,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(ly)) as ly,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(pz)) as pz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(xw)) as xw,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(gj)) as gj,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(gn)) as gn,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(zz)) as zz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(yfyl)) as yfyl,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(tstf)) as tstf,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(zysx)) as zysx,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(dx)) as dx")
		->select();
	}
}

?>