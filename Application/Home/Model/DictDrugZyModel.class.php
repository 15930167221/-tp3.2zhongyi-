<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/12
 * Time: 17:40
 */
namespace Home\Model;
use Think\Model;

class DictDrugZyModel extends Model
{
    protected $tableName = 'dict_drug_zy';

    public function getYaoJieByCode(array $condition)
    {
        $res = $this->alias('z')
            ->join('left join DICT_DRUG_ZY_MX as m on z.drug_code = m.drug_code')
            ->field('z.drug_name, z.name_alias, z.drug_units, z.price, z.input_code,
            CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.ly)) as ly,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.pz)) as pz,
            CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.xw)) as xw,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.gj)) as gj,
            CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.gn)) as gn,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.zz)) as zz,
            CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.yfyl)) as yfyl,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.zysx)) as zysx,
            CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.xz)) as xz,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.syz)) as syz,
            CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.lcyy)) as lcyy,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.jbyy)) as jbyy,
            CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.xdyj)) as xdyj,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.cydbf)) as cydbf,
            CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.zc)) as zc,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(m.tstf)) as tstf
            ')
            ->where($condition)
            ->find();
//        echo $this->getLastSql();
        return $res;
    }
}