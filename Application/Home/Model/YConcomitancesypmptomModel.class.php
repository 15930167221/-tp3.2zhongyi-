<?php
/**
 * Created by PhpStorm.
 * User: Miracle7Kill
 * Date: 2017/2/28
 * Time: 15:33
 */
namespace Home\Model;

use Think\Model;

class YConcomitancesypmptomModel extends Model
{
    protected $tableName = 'y_concomitancesypmptom';
    // 按条件查询附带解密
    public function getBmInfoName(array $condition)
    {
        return $this->where($condition)->Distinct(true)->
        field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME, TREE")->order('tree')->select();
    }
}