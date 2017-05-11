<?php
namespace Home\Model;

use Think\Model;

class JkdaXxModel extends Model
{
    protected $tableName = 'jkda_xx';//存放患者健康档案信息

    /**得到患者的健康档案信息
     * @param array $condition 查询条件
     * @return mixed
     */
    public function getOneInf(array $condition){
        return $this->where($condition)->find();
    }
}