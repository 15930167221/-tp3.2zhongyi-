<?php
namespace Admin\Controller;

use Admin\Model\AboutModel;
use Org\Util\Decrypt;
use Think\Page;

/**
 * 医疗机构管理
 * Class AboutController
 * @package Admin\Controller
 */
class AboutController extends AdminController
{

    protected function _initialize()
    {
        parent::_initialize();
        $this->mod = new AboutModel();
    }
    public function index()
    {
        $param = I('get.');
        $where = array();
        (!empty($param)) && $where['hospital'] = array('like', "%{$param['name']}%");
        //总条数
        $count = $this->mod->getCount($where);
        $page = new Page($count, C('PAGE_SIZE'));
        //列表信息
        $res = $this->mod->getListFromPage($where, $page);

        $this->assign('param', $param['name']);
        $this->assign('list', array(
            'total' => $count,
            'list' => $res,
            'page' => $page->show()
        ));
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            $data = I('post.');
            (empty($data['hospital']) || empty($data['hosp_code']) || empty($data['pid'])) && $this->error('请输入必填信息');
            //读取机构配置文件
            $code = new Decrypt();
            $content = $code->decode(C('SALT'));
            $arr = explode(',', $content);
            if (in_array($data['hospital'],$arr)) {
                $data['reg_date'] = date('Y-m-d H:i:s');
                $res = $this->mod->insertHosp($data);
                $res === true ? $this->success('添加成功') : $this->error('添加失败');
            } else {
                $this->error('此机构暂时不在系统维护中');
            }
        } else {
            $sele = $this->mod->getDepart();
            $this->assign('sele', $sele);
            $this->display();
        }
    }

    public function edit()
    {
        if (IS_POST) {
            $condition['id'] = I('post.id');
            $data = I('post.');
            array_shift($data);
            $res = $this->mod->updateHosp($condition, $data);
            $res === true ? $this->success('编辑成功') : $this->error('编辑失败');
        } else {
            $condition['id'] = I('get.id');
            empty($condition['id']) && $this->error('无效参数');
            $res = $this->mod->getInfoById($condition);
            $sele = $this->mod->getDepart();
            $this->assign('sele', $sele);
            $this->assign('info', $res);
            $this->display();
        }
    }

    public function delete()
    {
        $condition['id'] = I('post.id');
        empty($condition['id']) && $this->error('无效参数');
        $res = $this->mod->where($condition)->delete();
        if ($res) {
            $this->ajaxReturn(array('status' => true, 'msg' => '删除成功!'));
        } else {
            $this->ajaxReturn(array('status' => false, 'msg' => '删除失败!'));
        }
    }
}