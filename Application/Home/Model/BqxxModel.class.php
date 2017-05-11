<?php
namespace Home\Model;

use Think\Model;

class BqxxModel extends Model
{
    protected $tableName = 'Bqxx';//存放健康档案部分信息
    /**根据相关条件查询出健康档案的部分信息
     * @param array $condition
     * @return mixed
     */
    public function getOneBqxxInf(array $condition){
        return $this->where($condition)->find();
    }

    /**历史完成就诊记录信息
     * @param array $condition
     * @return mixed
     */
    public function getHis(array $condition){
        return $this->join('station_p on bqxx.BR_ID=station_p.br_id and bqxx.XH=station_p.xh')->field('bqxx.BR_ID,bqxx.XH,bqxx.jz_date')->where($condition)->select();
    }
}