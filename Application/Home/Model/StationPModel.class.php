<?php
namespace Home\Model;

use Think\Model;

class StationPModel extends Model
{
    protected $tableName = 'station_p';

    /**得到患者的信息
     * @param array $condition  检索条件
     * @return mixed 返回一位数组和单条数据
     */
    public function getUserInf(array $condition){
        return $this->where($condition)->find();
    }

    /**得到患者的信息
     * @param array $condition 查询条件
     * @return mixed  返回二维数组或者多条数据
     */
    public function getMoreUserInf(array $condition){
        return $this->field("br_id,br_name,dw,tel,fax,e_mail,p_date,jz_date,cs_date,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,nl as nlt,pass")->where($condition)->select();
    }

    /**得到患者登记的条数
     * @param array $condition 查询条件
     * @return mixed 返回患者的记录条数
     */
    public function getUserCount(array $condition){
        return $this->where($condition)->count();
    }

    /**添加用户
     * @param array $inf 需要添加用户的信息
     * @return mixed
     */
    public function addUser(array $inf){
        return $this->add($inf);
    }
}