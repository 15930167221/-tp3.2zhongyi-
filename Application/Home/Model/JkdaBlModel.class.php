<?php
namespace Home\Model;

use Think\Model;

class JkdaBlModel extends Model
{
    protected $tableName = 'jkda_bl';

    /**查询健康档案的病历信息
     * @param array $condition 查询条件
     * @return mixed 返回病历信息的二维数组
     */
    public function getBlInf(array $condition){
        return $this->where($condition)->select();
    }
}