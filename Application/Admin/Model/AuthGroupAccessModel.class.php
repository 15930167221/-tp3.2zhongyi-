<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/20
 * Time: 11:01
 */
namespace Admin\Model;

use Think\Model;

class AuthGroupAccessModel extends Model
{
    protected $tableName = 'auth_group_access';

    public function insertId($data)
    {
        if (!$this->create($data)) {
            return $this->getError();
        }
        return $this->add($data) ? true : false;
    }

    public function updateId($where, $data)
    {
        if (!$this->create($data)) {
            return $this->getError();
        }
        return $this->where($where)->save() ? true : false;
    }
}