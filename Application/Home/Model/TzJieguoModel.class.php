<?php
namespace Home\Model;

use Think\Model;

class TzJieguoModel extends Model
{
    protected $tableName = 'tz_jieguo';//存储患者根据答题结果生成的体制信息

    /**得到患者的个人信息和答题后生成的辨识结果
     * @param array $condition 查询条件
     * @return mixed 返回一维数组
     */
    public function getUserInfAndAnsJG(array $condition){
        return $this->join('station_p on tz_jieguo.id=station_p.xh and tz_jieguo.bianhao=station_p.br_id')->where($condition)->find();
    }

    /**得到患者的答题结果
     * @param array $condition
     * @return mixed 返回患者的答题结果的二维数组
     */
    public function getUserAnsJG(array $condition){
        return $this->where($condition)->select();
    }

    /**存储患者的答题结果
     * @param array $inf 要添加的信息
     * @return mixed
     */
    public function addJGInf(array $inf){
        return $this->add($inf);
    }
    /**更新患者的答题结果信息
     * @param array $inf 要更新的数据
     * @return bool
     */
    public function  saveJGInf(array $inf){
        return $this->save($inf);
    }
}