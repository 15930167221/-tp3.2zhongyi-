<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/10
 * Time: 10:07
 */
namespace Admin\Controller;

use Admin\Model\MembersModel;
use Think\Page;

/**
 * 后台用户
 * Class MembersController
 * @package Admin\Controller
 */
class MembersController extends AdminController
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->mod = new MembersModel();
    }
    public function index()
    {
        $param = I('get.');
        $where = array();
        (!empty($param)) && $where['name'] = array('like',"%{$param['name']}%");
        //总条数
        $count = $this->mod->getCount($where);
        $page = new Page($count, C('PAGE_SIZE'));
        //列表信息
        $res = $this->mod->getListFromPage($where, $page);
        //echo $this->mod->getLastSql();
        $this->assign('info', array('list' => $res,'count' => $count, 'page' => $page->show()));
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            $data = I('post.');
            ((empty($data['name'])) || (empty($data['password'])) || (empty($data['phone']))) && $this->error('请输入必填项!');
            ($data['password'] != $data['password2']) && $this->error('两次密码输入不一致');
            $data['password'] = md5($data['password']);
            $data['status'] = 1;
            $data['create_time'] = time();
            unset($data['password2']);
            $res = $this->mod->insertInfo($data);
            $dataRes['uid'] = $this->mod->getLastInsID();
            $dataRes['group_id'] = $data['auid'];
            D('auth_group_access')->insertId($dataRes);
            $res === true ? $this->success('添加成功') : $this->error('添加失败');
        } else {
            //获取角色
            $rule = D('auth_group')->getListInfo();
            $sele = D('About')->getDepart();
            $this->assign('sele', $sele);
            $this->assign('rule', $rule);
            $this->display();
        }
    }

    public function edit()
    {
        if (IS_POST) {
            $condition['id'] = I('post.id');
            $data = I('post.');
            array_shift($data);
            unset($data['password2']);
            $data['update_time'] = time();
            $res = $this->mod->updateInfo($condition, $data);
            D('auth_group_access')->updateId(array('uid' => $condition['id']),array('group_id' => $data['auid']));
            $res === true ? $this->success('编辑成功') : $this->error('编辑失败');
        } else {
            $condition['id'] = I('get.id');
            empty($condition['id']) && $this->error('无效参数');
            $info = $this->mod->getInfoById($condition);
            $rule = D('auth_group')->getListInfo();
            $sele = D('About')->getDepart();
            $this->assign('sele', $sele);
            $this->assign('rule', $rule);
            $this->assign('info', $info);
            $this->display();
        }
    }

    public function delete()
    {
        $condition['id'] = I('post.id');
        empty($condition['id']) && $this->error('无效参数');
        $res = $this->mod->where($condition)->delete();
        D('auth_group_access')->where(array('uid' => $condition['id']))->delete();
        if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
    }
}