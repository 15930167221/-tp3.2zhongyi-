<?php
/**
 * Created by PhpStorm.
 * User: Miracle7Kill
 * Date: 2017/3/6
 * Time: 18:24
 */

namespace Admin\Model;

use Think\Model\RelationModel;
use Think\Page;

class MembersModel extends RelationModel
{
    /**
     * 登录检查
     * @acess public
     * @param $param
     * @return mixed
     */
    public function login($param)
    {
        return $this->getInfo("name = '%s' and password = '%s'", $param);
    }

    /**
     * 获取用户信息
     * @acess public
     * @param $where 条件
     * @param $param 参数
     * @return object
     */
    public function getInfo($where, $param)
    {
        return $this->relation(true)->where($where, $param)->find();
    }

    public function getListFromPage(array $where, Page $page)
    {
        return $this->relation(true)
            ->where($where)->limit($page->firstRow, $page->listRows)
            ->order('id desc')->select();

    }

    public function getCount(array $where)
    {
        return $this->where($where)->count();
    }

    public function getInfoById(array $where)
    {
        return $this->where($where)->find();
    }

    public function insertInfo($data)
    {
        if (!$this->create($data)) {
            return $this->getError();
        }
        return $this->add($data) ? true : false;
    }

    public function updateInfo($where, $data)
    {
        if (!$this->create($data)) {
            return $this->getError();
        }
        return $this->where($where)->save($data) ? true : false;
    }
}