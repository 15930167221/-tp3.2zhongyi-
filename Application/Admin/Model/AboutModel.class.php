<?php
namespace Admin\Model;

use Think\Model;
use Think\Page;

/**
 * Class AboutModel
 * @package Admin\Model
 */
class AboutModel extends Model
{
    protected $tableName = 'about';

    public function getListFromPage(array $where, Page $page)
    {
        return $this->where($where)->limit($page->firstRow, $page->listRows)->select();
    }

    public function getCount(array $where)
    {
        return $this->where($where)->count();
    }

    public function insertHosp(array $data)
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

    public function updateHosp($where, $data)
    {
        if (!$this->create($data)) {
            return $this->getError();
        }
        return $this->where($where)->save($data) ? true : false;
    }

    public function getDepart()
    {
        return $this->field('id, hospital, levelid')->select();
    }
}