<?php
namespace Home\Model;

use Think\Model;

class XyNameModel extends Model
{
    protected $tableName = 'xy_name';//存放健康档案部分信息

    /**返回西药病名
     * @return mixed
     */
    public function getXyName(){
        return $this->field('name')->select();
    }
}
