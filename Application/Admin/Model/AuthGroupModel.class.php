<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/16
 * Time: 17:48
 */
namespace Admin\Model;

use Think\Model;
use Think\Page;

class AuthGroupModel extends Model
{
    protected $tableName = 'auth_group';

    public function insertInfo(array $data)
    {
        if (!$this->create($data)) {
            return $this->getError();
        }
        return $this->add() ? true : fasle;
    }

    public function getListInfo()
    {
        return $this->field('id, title, details')->select();
    }

    public function getInfoById($where)
    {
        return $this->where($where)->find();
    }

    public function updateInfo($where, $data)
    {
        if (!$this->create($data)) {
            return $this->getError();
        }
        return $this->where($where)->save($data) ? true : false;
    }
}