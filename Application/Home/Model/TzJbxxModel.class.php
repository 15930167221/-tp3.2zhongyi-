<?php
namespace Home\Model;

use Think\Model;

class TzJbxxModel extends Model
{
    protected $tableName = 'tz_jbxx';//存储患者的答题信息

    /**得到患者的选项信息
     * @param array $condition
     * @return mixed 返回一维数组
     */
    public function getUserAns(array $condition){
        return $this->where($condition)->find();
    }

    /**得到患者的答题信息
     * @param array $condition
     * @return mixed 返回二维数组
     */
    public function getUserAnsInf(array $condition){
        return $this->where($condition)->select();
    }

    /**添加患者的选项信息
     * @param array $inf
     * @return mixed
     */
    public function addAnsInf(array $inf){
        return $this->add($inf);
    }
    /**更新患者的选项信息
     * @param array $inf 要更新的数组
     * @return mixed
     */
    public function saveAnsInf(array $inf){
        return $this->save($inf);
    }
}