<?php
namespace Home\Model;

use Think\Model;

class TzQuestionModel extends Model
{
    protected $tableName = 'tz_question';//体质辨识答题界面题目信息

    /**体质辨识题目信息的检索
     * @param array $tj  检索条件
     * @return mixed 返回二维数组
     */
    public function getQuestionInf(array $tj){
        return $this->where($tj)->select();
    }
}