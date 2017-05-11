<?php
/**
 * Created by PhpStorm.
 * User: Miracle7Kill
 * Date: 2017/3/6
 * Time: 18:24
 */

namespace Admin\Model;

use Think\Model;
use Think\Page;

class SysDmJxModel extends Model
{
   

    /**
     * 获取用户信息
     * @acess public
     * @param $where 条件
     * @param $param 参数
     * @return object
     */
    

    public function getListFromPage(array $where, Page $page)
    {
        return $this
            ->where($where)->limit($page->firstRow, $page->listRows)
            ->order('jxdm')->select();

    }

    public function getCount(array $where)
    {
        return $this->where($where)->count();
    }

}