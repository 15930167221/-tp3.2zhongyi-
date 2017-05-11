<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/15
 * Time: 14:22
 */
namespace Admin\Model;

use Think\Model;

class AuthRuleModel extends Model
{
    protected $tableName = 'auth_rule';

    public function getRule($pid = 0)
    {
        $arrRule = array();
        $res = $this->where(array('pid' => $pid))
            ->order('pid')->select();
        foreach($res as $key => $val) {
            if ($val['pid'] == $pid) {
                $val['child'] = $this->getRule($val['id']);
                if (empty($val['child'])) {
                    unset($val['child']);
                }
                array_push($arrRule, $val);
            }
        }
        return $arrRule;
    }
}