<?php
/**
 * Created by PhpStorm.
 * User: Miracle7Kill
 * Date: 2017/2/28
 * Time: 15:33
 */
namespace Home\Model;

use Think\Model;

class TcdZybmModel extends Model
{
    protected $tableName = 'tcd_zybm';

    public function getInfoByZyCode(array $condition)
    {
        return $this->where($condition)->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(ZX)) as ZX, CONVERT(VARCHAR(MAX),DECRYPTBYKEY(ZF)) as ZF, CONVERT(VARCHAR(MAX),DECRYPTBYKEY(cf_name)) as cf_name, cf_tree, CONVERT(VARCHAR(MAX),DECRYPTBYKEY(explain)) as explain")->select();
    }
    // 按病名查询附带解密
    public function getBmInfoName(array $condition)
    {
        return $this->where($condition)->Distinct(true)->
        field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME, CODE")->order('code')->select();
    }
    // 按条件查询附带解密
    public function getBmInfo(array $condition)
    {
        return $this->where($condition)->Distinct(true)->
        field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(ZX)) as ZX,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(ZF)) as ZF,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(CF_NAME)) as CF_NAME,CF_TREE,code")->order('CODE')->select();
    }
    // 按条件查询附带解密(只查名字 和code)
    public function getBmInfobmchax(array $condition)
    {
        return $this->where($condition)->Distinct(true)->
        field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,code")->order('CODE')->select();
    }

    /**健康档案界面，中医诊断
     * @param array $condition
     * @return mixed 返回中西病名
     */
    public function getZyBm(array $condition){
        return $this->distinct(true)->where($condition)->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME")->select();
    }

}