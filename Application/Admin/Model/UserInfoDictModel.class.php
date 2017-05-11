<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/20
 * Time: 17:45
 */
namespace Admin\Model;

use Think\Model;
use Think\Page;

class UserInfoDictModel extends Model
{
    protected $tableName = 'user_info_dict';

    public function getListFromPage(array $where, Page $page)
    {
        return $this->where($where)->limit($page->firstRow, $page->listRows)->select();
    }

    public function getCount(array $where)
    {
        return $this->where($where)->count();
    }

    public function insertUser(array $data)
    {
        if (!$this->create($data)) {
            return $this->getError();
        }
        return $this->add() ? true : false ;
    }

    public function getInfoById(array $where)
    {
        return $this->where($where)->find();
    }

    public function updateUser($where, $data)
    {
        if (!$this->create($data)) {
            return $this->getError();
        }
        return $this->where($where)->save($data) ? true : false;
    }
}