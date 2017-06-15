<?php
namespace Home\Controller;

use Think\Controller;
use Org\Util\AjaxPage;
class KaifangController extends PublicController
{
    protected function __initialize()
    {
        parent::_initialize();
    }
    public function bingMing()
    {
        $where['BM'] = '000';
        $data = M('tcd_zybm')->where($where)->Distinct(true)->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME, CODE")->order('CODE')->select();
        $this->assign('data', $data);
        $this->display();
    }
    //按病名查找
    public function ajaxtjbm()
    {
        $tjbm = I('post.tjbm');
        //接受ajax传过来的条件
        if (preg_match("/^[a-z]/i", $tjbm)) {
            $where['BM_INPUT'] = array('like', "%{$tjbm}%");
            $where['BM'] = '000';
            $data = D('tcd_zybm')->getBmInfobmchax($where);
            $this->ajaxReturn($data, 'json');
        } else {
            $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$tjbm}%");
            $where['BM'] = '000';
            $data = D('tcd_zybm')->getBmInfobmchax($where);
            //echo D('tcd_zybm')->getLastSql();
            $this->ajaxReturn($data, 'json');
        }
    }
    //ajax改变右侧证型 治法 等的值
    public function ajaxgaibiyouzhi()
    {
        $tjzuobingm = I('post.tjzuobingm');
        //接受ajax传过来的条件
        $condition['CODE'] = $tjzuobingm;
        //$data = $user->where("CODE = {$tjzuobingm}")->field('zx,zf,cf_name,cf_tree')->select();
        $data = D('tcd_zybm')->getInfoByZyCode($condition);
        $this->ajaxReturn($data);
    }
    //ajax根据查询条件改变证型治法值
    public function ajaxzhengxingzhif()
    {
        $tjbmzxzf = I('post.tjbmzxzf');
        //接受ajax传过来的病名code条件
        $zxzfjg = I('post.zxzfjg');
        //接受ajax传过来的条件
        $user = M("tcd_zybm");
        $where['ZX_INPUT'] = array('like', "%{$zxzfjg}%");
        $where['ZF_INPUT'] = array('like', "%{$zxzfjg}%");
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
        $map['CODE'] = $tjbmzxzf;
        // $where['_string'] = ' (ZX_INPUT like "{$zxzfjg}%")  OR ( ZF_INPUT like "{$zxzfjg}%") ';
        $data = D('tcd_zybm')->getBmInfo($map);
        $this->ajaxReturn($data);
    }
    //ajax改变下侧处方
    public function ajaxgaibianchufang()
    {
        $mod = M();
        $tjyouzhengxing = I('post.tjyouzhengxing');
        //接受ajax传过来的条件
        // /获取所属机构
        $dpment = session('dpment');
        $sql = "select  dict_drug_zy.drug_name,bz_cf.dw,bz_cf.sl,isnull((select top 1 name from dict_usage where code=bz_cf.yf),bz_cf.yf) as yf,bz_cf.serial_no FROM [bz_cf] \r\nINNER JOIN dict_drug_zy on dict_drug_zy.drug_code=bz_cf.ypdm  \r\nWHERE bz_cf.cfdm = '{$tjyouzhengxing}' and dict_drug_zy.department = '{$dpment}' order by serial_no";
        $res = $mod->query($sql);
        $this->ajaxReturn($res);
    }
    //证型治法页面
    public function zhengxing()
    {
        // $user = M("tcd_zybm");
        // $where['BM'] = '04';
        $where['BM'] = '000';
        $data = D('tcd_zybm')->getBmInfo($where);
        // $data = $user->where($where)->distinct(true)->field('name,zx,zf,cf_name,cf_tree')->select();
        // dump($data);
        $this->assign('data', $data);
        $this->display();
    }
    //ajax 页面2 按查询条件得出证型治法
    public function yeerajaxzxzf()
    {
        $yerzxzfjg = I('post.yerzxzfjg');
        //接受ajax传过来的条件
        //判断是否为空
        if (!empty($yerzxzfjg)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $yerzxzfjg)) {
                $where['ZX_INPUT'] = array('like', "%{$yerzxzfjg}%");
                $where['ZF_INPUT'] = array('like', "%{$yerzxzfjg}%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(ZX))'] = array('like', "%{$yerzxzfjg}%");
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(ZF))'] = array('like', "%{$yerzxzfjg}%");
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
        }
        $map['BM'] = "000";
        $data = D('tcd_zybm')->getBmInfo($map);
        // $data = $user->where($map)->field('name,zx,zf,cf_name,cf_tree')->select();
        $this->ajaxReturn($data);
    }
    //ajax 页面2处方赋值
    public function yeerchufangfuzhi()
    {
        $mod = M();
        $tjyouzhengxing = I('post.tjyouzhengxing');
        //接受ajax传过来的条件
        // /获取所属机构
        $dpment = session('dpment');
        $sql = "select  dict_drug_zy.drug_name,bz_cf.dw,bz_cf.sl,isnull((select top 1 name from dict_usage where code=bz_cf.yf),bz_cf.yf) as yf,bz_cf.serial_no FROM [bz_cf] \r\nINNER JOIN dict_drug_zy on dict_drug_zy.drug_code=bz_cf.ypdm  \r\nWHERE bz_cf.cfdm = '{$tjyouzhengxing}' and dict_drug_zy.department = '{$dpment}' order by serial_no";
        $res = $mod->query($sql);
        $this->ajaxReturn($res);
    }
    //治疗指南
    public function zhiLiaoZhinan()
    {
        $user = M("jbxx");
        $where['XXLBDM'] = '16';
        $data = $user->where($where)->order('xxdm')->select();
        // dump($data);
        $this->assign("data", $data);
        $this->display();
    }
    // ajax治疗指南点击出现子类
    public function ajaxzlznzilei()
    {
        $zhuaxuyhaoid = I('post.zhuaxuyhaoid');
        //接受ajax传过来的条件
        $user = M('v_tcd_zyxk');
        $where['BM'] = $zhuaxuyhaoid;
        $databingm = $user->where($where)->field('name, code')->order('code')->select();
        if ($databingm) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $databingm));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $databingm));
        }
    }
    //
    public function yeerajaxtjbm()
    {
        $tjbm = I('post.tjbm');
        //接受ajax传过来的条件
        if (preg_match("/^[a-z]/i", $tjbm)) {
            $user = M("v_tcd_zyxk");
            $where['input_code'] = array('like', "%{$tjbm}%");
            $data = $user->where($where)->distinct(true)->field('BM,name,code')->order('code')->select();
            // $where['bm_input'] = array('like',"'$tjbm%'");
            // $data = $user->where("bm_input like '".$user."%' ")->field('name')->select();
            // $data = $user->where("bm_input = 'XEGM' ")->field('name')->select();
            $this->ajaxReturn($data);
        } else {
            $user = M("v_tcd_zyxk");
            $where['NAME'] = array('like', "%{$tjbm}%");
            $data = $user->where($where)->distinct(true)->field('BM,name,code')->order('code')->select();
            // $where['bm_input'] = array('like',"'$tjbm%'");
            // $data = $user->where("bm_input like '".$user."%' ")->field('name')->select();
            // $data = $user->where("bm_input = 'XEGM' ")->field('name')->select();
            $this->ajaxReturn($data);
        }
    }
    //页面3ajax改变右侧证型
    public function yesanajaxyouzhengxing()
    {
        $tjzuobingm = I('post.tjzuobingm');
        //接受ajax传过来的条件
        $where['zyyxh_code'] = $tjzuobingm;
        $data = D('tcd_zybm')->getInfoByZyCode($where);
        $this->ajaxReturn($data);
    }
    //页面3按证型搜索赋值
    public function yesanajaxzhengxingzhif()
    {
        $tjbmzxzf = I('post.tjbmzxzf');
        //接受ajax传过来的病名code条件
        $zxzfjg = I('post.zxzfjg');
        //接受ajax传过来的条件
        $user = M("tcd_zybm");
        $where['ZX_INPUT'] = array('like', "%{$zxzfjg}%");
        $where['ZF_INPUT'] = array('like', "%{$zxzfjg}%");
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
        $map['zyyxh_code'] = $tjbmzxzf;
        // $where['_string'] = ' (ZX_INPUT like "{$zxzfjg}%")  OR ( ZF_INPUT like "{$zxzfjg}%") ';
        $data = $user->where($map)->field('convert(varchar(max), DECRYPTBYKEY(ZX)) AS ZX,convert(varchar(max), DECRYPTBYKEY(ZF)) AS ZF,convert(varchar(max), DECRYPTBYKEY(cf_name)) AS cf_name,convert(varchar(max), DECRYPTBYKEY(explain)) AS explain,cf_tree')->select();
        $this->ajaxReturn($data);
    }
    //页面3 ajax 点击证型 改变处方
    public function yesanajaxgaibianchufang()
    {
        $mod = M();
        $tjyouzhengxing = I('post.tjyouzhengxing');
        //接受ajax传过来的条件
        // /获取所属机构
        $dpment = session('dpment');
        $sql = "select  dict_drug_zy.drug_name,bz_cf.dw,bz_cf.sl,isnull((select top 1 name from dict_usage where code=bz_cf.yf),bz_cf.yf) as yf,bz_cf.serial_no FROM [bz_cf] \r\nINNER JOIN dict_drug_zy on dict_drug_zy.drug_code=bz_cf.ypdm  \r\nWHERE bz_cf.cfdm = '{$tjyouzhengxing}' and dict_drug_zy.department = '{$dpment}' order by serial_no";
        $res = $mod->query($sql);
        $this->ajaxReturn($res);
    }
    /*******经典方开始********/
    public function jingDian()
    {
        $model = M("y_recipemain");
        /*$sql1="select tree,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as name,type,explain,EFFICACY,MAINCURE,ICD_CODE,spell from Y_RECIPEMAIN A where type='1' AND TREE IN(SELECT MIN(TREE) FROM Y_RECIPEMAIN B WHERE B.NAME=A.NAME) and tree in(select cfdm from bz_cf) order by type,tree";*/
        $sql = "select tree,name,type,explain,EFFICACY,MAINCURE,ICD_CODE,spell from V_RECIPEMAIN order by type,tree";
        $cons = $model->query($sql);
        $this->assign('cons', $cons);
        $this->display();
    }
    function impidajax()
    {
        if (preg_match("/^[a-z]/i", $_POST['pym'])) {
            $sql = "select tree,name,type,explain,EFFICACY,MAINCURE,ICD_CODE,spell from V_RECIPEMAIN where spell like '%" . $_POST[pym] . "%' order by type,tree";
        } else {
            $sql = "select tree,name,type,explain,EFFICACY,MAINCURE,ICD_CODE,spell from V_RECIPEMAIN where name like '%" . $_POST[pym] . "%' order by type,tree";
        }
        $fjm = M('y_recipemain')->query($sql);
        $this->ajaxReturn($fjm);
    }
    function fangjie()
    {
        $model = M('bz_cf');
        $sql = "select dict_drug_zy.drug_name,bz_cf.dw,bz_cf.sl,isnull((select top 1 name from dict_usage where code=bz_cf.yf),bz_cf.yf) as yf,bz_cf.serial_no FROM bz_cf INNER JOIN dict_drug_zy on dict_drug_zy.drug_code=bz_cf.ypdm WHERE bz_cf.cfdm = '{$_POST['tree']}' and dict_drug_zy.department=" . session('dpment') . " order by serial_no";
        $result = $model->query($sql);
        $this->ajaxReturn($result);
    }
    function fjcon()
    {
        $data = M('y_recipemain')->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,CODE,SPELL,TREE,EXPLAIN,SOURCE,EFFICACY,MAINCURE,TSYF")->where("tree='{$_POST['tree']}'")->select();
        $this->ajaxReturn($data);
    }
    /*******经典方结束********/
    /*******经验方开始********/
    public function jingYan()
    {
        $model = M('experience');
        $zyyp = M('drug_dict');
        $zy_name = M('dict_drug_zy_alias');
        $cons = $model->where("operator_code=" . session(wh_userId))->order('id asc')->select();
        $zyypres = $zyyp->where("drug_indicator = '2'")->select();
        $this->assign('zyypres', $zyypres);
        $this->assign('mcon', $cons);
        $this->display();
    }
    public function jingYanajax()
    {
        $str = $_POST['str'];
        $drug_dict = M('v_dict_drug_zy');
        $dep = M('user_info_dict');
        $res = $dep->field('department')->where("id =" . session(wh_userId))->select();
        $department = $res[0]['department'];
        if ($str == '') {
            return false;
        }
        $jyjg = $drug_dict->field("input_code,flag,code,name,xw,other_name")->where("input_code like '%" . $str . "%' and department = '{$department}'")->order('flag,input_code')->select();
        $this->ajaxReturn($jyjg);
    }
    public function jingYanajax2()
    {
        $str = $_POST['str'];
        $drug_dict = M('v_dict_drug_zy');
        // $dep = M('user_info_dict');
        // $res = $dep -> field('department') -> where("id =".session(wh_userId)) -> select();
        $department = $_SESSION['dpment'];

        $whe['input_code'] = array('like', "%{$str}%");
        $jyjg = $drug_dict->field("input_code,flag,code,name,xw,other_name")->where($whe)->where("department={$department}")->order('flag,input_code')->select();
        $this->ajaxReturn($jyjg);
    }
    //经验开方提交的数据
    public function jysubmit()
    {
        $cons = I('post.');
        // dump($cons);die;
        $model = M('experience_detail');
        $info = $model->where("id='{$_GET['id']}'")->select();
        if (!empty($info)) {
            $model->where("id='{$_GET['id']}'")->delete();
        }
        $num = count($cons[drug_name]);
        for ($i = 0; $i < $num; $i++) {
            foreach ($cons as $key => $value) {
                $data[$i][$key] = $value[$i];
            }
        }
        foreach ($data as $k => $v) {
            $v[id] = intval($_GET[id]);
            $v[amount] = floatval($data[$k][amount]);
            $code = D('DrugDict')->getCode("drug_name='{$v['drug_name']}'");
            $v[drug_code] = $code[0][drug_code];
            $model->add($v);
        }
        header("Location:" . $_SERVER[HTTP_REFERER]);
    }
    function rightcon()
    {
        $model = M('experience_detail');
        $cfcontents = $model->where("id='{$_POST['id']}'")->order('drug_no asc')->select();
        $this->ajaxReturn($cfcontents);
    }
    function addsub()
    {
        $spell = M('dict_hzpy');
        $data = I('post.');
        $split = str_split($data[name], 3);
        foreach ($split as $v) {
            $con[] = $spell->where("BHZ='{$v}'")->find();
        }
        foreach ($con as $v) {
            $pym[] = $v['bsm'];
        }
        $pym = implode('', $pym);
        $model = M('experience');
        $data[Attending] = trim($data[Attending]);
        $data[input_code] = $pym;
        $data[operator_code] = session(wh_userId);
        $data[create_date] = date(Ymd);
        $info = $model->add($data);
        if ($info) {
            echo "<script>self.location=document.referrer;</script>";
        }
    }
    function whaddsub()
    {
        $spell = M('dict_hzpy');
        $data = I('post.');
        $split = str_split($data[name], 3);
        foreach ($split as $v) {
            $con[] = $spell->where("BHZ='{$v}'")->find();
        }
        foreach ($con as $v) {
            $pym[] = $v['bsm'];
        }
        $pym = implode('', $pym);
        $model = M('experience');
        $data[Attending] = trim($data[Attending]);
        $data[input_code] = $pym;
        $data[operator_code] = session(wh_userId);
        $data[create_date] = date(Ymd);
        $info = $model->add($data);
        if ($info) {
            echo "<script>self.location=document.referrer;</script>";
        }
    }
    function aexpe()
    {
        $model = M('experience');
        if (preg_match("/^[a-z]/i", $_POST['pym'])) {
            $cfm = $model->where("input_code like '%" . $_POST[pym] . "%' and operator_code=" . session(wh_userId))->select();
        } else {
            $cfm = $model->where("name like '%" . $_POST[pym] . "%' and operator_code=" . session(wh_userId))->select();
        }
        $this->ajaxReturn($cfm);
    }
    function cfdel()
    {
        $model = M('experience');
        $model->where("id='{$_POST['id']}'")->delete();
        $detail = M('experience_detail');
        $info = $detail->where("id='{$_POST['id']}'")->delete();
        $this->ajaxReturn($info);
    }
    function cfupdate()
    {
        $model = M('experience');
        $upcons = I('post.');
        $spell = M('dict_hzpy');
        $split = str_split($data[name], 3);
        foreach ($split as $v) {
            $con[] = $spell->where("BHZ='{$v}'")->find();
        }
        foreach ($con as $v) {
            $pym[] = $v['bsm'];
        }
        $pym = implode('', $pym);
        $data['name'] = $upcons['name'];
        $data['Attending'] = trim($upcons['Attending']);
        $data['input_code'] = $pym;
        $data['update_date'] = date(Ymd);
        $info = $model->where("id='{$upcons['id']}'")->save($data);
        if ($info) {
            echo "<script>self.location=document.referrer;</script>";
        }
    }
    /*******经验方结束********/

    //页面6辨证处方
    public function bianZheng(){
        $user = D("y_mainsypmpotm");
        // 获取主症的分类
        // ISSHOW 字段为0是主键 为1是点击主键对应的值
        $where['ISSHOW'] = 0;
        $data = M('y_mainsypmpotm')->where($where)->Distinct(true)->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME, TREE")->order('TREE')->select();
        $this->assign("data", $data);
        $this->display();
    }
    //  ------------------------------------------主症开始----------------------------
    //页面6 ajax 点击主症
    public function yeliuajaxdianzz()
    {
        $dianjizhuzheng = I('post.dianjizhuzheng');
        //接受传过来的主症tree号
        $user = D("y_mainsypmpotm");
        $where['TREE'] = array('like', "{$dianjizhuzheng}%");
        $where['ISSHOW'] = 1;
        // $data = $user->where($where)->field('name,code')->select();
        $data = $user->getBmInfoName($where);
        // $this->ajaxReturn($data);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 主症下搜索框搜索证型
    public function yeliuajaxzhengxingss()
    {
        $zxzfjg = I('post.zxzfjg');
        //接受传过来的输入的值
        $tjbmzxzf = I('post.tjbmzxzf');
        //接受传过来的主症tree号
        $user = D("y_mainsypmpotm");
        $where['TREE'] = array('like', "{$tjbmzxzf}%");
        $where['ISSHOW'] = 1;
        //判断是否为空
        if (!empty($zxzfjg)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $zxzfjg)) {
                $where['SPELL'] = array('like', "%{$zxzfjg}%");
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$zxzfjg}%");
            }
        }
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 点击设为常用选择
    public function yeliuajaxchangyongxz()
    {
        $zhuzheng = I('post.zhuzheng');
        //接受传过来的输入的值
        $pieces = explode(" ", $zhuzheng);
        //转为数组
        $user = M("y_mainsypmpotm");
        $where['TREE'] = array("in", $pieces);
        $where['ISSHOW'] = 1;
        $gaidong['MNEMONIC'] = '1';
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //主症下分类确定按钮
    public function zhuzxcodezhuanhz()
    {
        $dozhuzheng = I('post.dozhuzheng');
        //接受传过来的输入的值
        $pieces = explode(" ", $dozhuzheng);
        //转为数组
        $user = D("y_mainsypmpotm");
        $where['TREE'] = array("in", $pieces);
        $where['ISSHOW'] = 1;
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 主症下检索搜索框
    public function yeliuzhuzhengjssousuokuang()
    {
        $user = D("y_mainsypmpotm");
        $tjzzjs = I('post.tjzzjs');
        //接受传过来的输入的值
        // ISSHOW = 1 为主症下的症状
        $where['ISSHOW'] = 1;
        //判断是否为空
        if (!empty($tjzzjs)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $tjzzjs)) {
                $where['SPELL'] = array('like', "%{$tjzzjs}%");
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$tjzzjs}%");
            }
        }
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 主症设为常用选择
    public function yeliuajaxzhuzsheweicyxx()
    {
        $sheweicycode = I('post.sheweicycode');
        //接受传过来的输入的值
        $user = M("y_mainsypmpotm");
        $where['CODE'] = $sheweicycode;
        $where['ISSHOW'] = 1;
        $gaidong['MNEMONIC'] = "1";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 主症取消常用选择
    public function yeliuajaxzhuzquxcyxx()
    {
        $quxiaocycode = I('post.quxiaocycode');
        //接受传过来的输入的值
        $user = M("y_mainsypmpotm");
        $where['TREE'] = $quxiaocycode;
        $where['ISSHOW'] = 1;
        $gaidong['MNEMONIC'] = " ";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 主症下常用选择搜索
    public function yeliuajaxzhuzcyxzss()
    {
        $cyxztj = I('post.cyxztj');
        //接受传过来的输入的值
        $user = D("y_mainsypmpotm");
        $where['ISSHOW'] = 1;
        $where['MNEMONIC'] = 1;
        //判断是否为空
        if (!empty($cyxztj)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $cyxztj)) {
                $where['SPELL'] = array('like', "%{$cyxztj}%");
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$cyxztj}%");
            }
        }
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //  ---------------------------------------------------------主症结束-----------
    //  ---------------------------------------------------------兼症开始-----------
    //页面6 ajax 兼症下分类赋值
    public function yeliuAjaxJZdijzchs()
    {
        $zhcztj = I('post.zhcztj');
        //接受传过来的输入的值
        $jioj = trim($zhcztj);
        //去除最后的空格
        $user = D("y_concomitancesypmptom");
        $where['TREE'] = array('like', "{$jioj}%");
        $where['KIND'] = 1;
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 兼症分类下搜索框搜索证型
    public function yeliuAjaxjzss()
    {
        $zxzfjg = I('post.zxzfjg');
        //接受传过来的输入的值
        $zhuzhengTree = I('post.zhuzhengTree');
        //接受传过来的主症tree号
        $dozhuzhengTree = trim($zhuzhengTree);
        //去除最后的空格
        $user = D("y_concomitancesypmptom");
        //模糊匹配符合主症的tree
        $where['TREE'] = array('like', "{$dozhuzhengTree}%");
        //判断是否是字母
        if (preg_match("/^[a-z]/i", $zxzfjg)) {
            $where['SPELL'] = array('like', "%{$zxzfjg}%");
        } else {
            $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$zxzfjg}%");
        }
        $where['KIND'] = 1;
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 兼症分类设为常用选择
    public function yeliuAjaxJZFensheweicyxx()
    {
        $jzfenlsheweicycode = I('post.jzfenlsheweicycode');
        //接受传过来的输入的值
        $user = M("y_concomitancesypmptom");
        $where['TREE'] = $jzfenlsheweicycode;
        $where['KIND'] = 1;
        $gaidong['MNEMONIC'] = "1";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 兼症检索分页
    public function yeliuAjaxjzjsfenyefuzhi()
    {
        $jzuser = D("y_concomitancesypmptom");
        $jzjswhere['KIND'] = 1;
        $jzjsdata = $jzuser->getBmInfoName($jzjswhere);
        $this->ajaxReturn($jzjsdata);
        // $this->assign("jzjsdata",$jzjsdata);
    }
    //页面6 ajax 兼症检索设为常用选择
    public function yeliuAjaxJZjssheweicyxx()
    {
        $jzjssheweicycode = I('post.jzjssheweicycode');
        //接受传过来的输入的值
        $user = M("y_concomitancesypmptom");
        $where['TREE'] = $jzjssheweicycode;
        $where['KIND'] = 1;
        $gaidong['MNEMONIC'] = "1";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 兼症检索下搜索框赋值
    public function yeliuAjaxjzjssousuofuzhi()
    {
        $user = D("y_concomitancesypmptom");
        $zxzfjg = I('post.zxzfjg');
        //接受传过来的输入的值
        $zhcztj = I('post.zhcztj');
        //接受传过来的主症号
        $dozhuzhengTree = trim($zhcztj);
        //去除最后的空格
        $where['TREE'] = array('like', "{$dozhuzhengTree}%");
        $where['KIND'] = 1;
        //判断是否为空
        if (!empty($zxzfjg)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $zxzfjg)) {
                $where['SPELL'] = array('like', "%{$zxzfjg}%");
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$zxzfjg}%");
            }
        }
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 兼症常用选择中取消常用选择
    public function jzajaxcyQuXiancy()
    {
        $quxiaocycode = I('post.quxiaocycode');
        //接受传过来的输入的值
        $user = M("y_concomitancesypmptom");
        $where['TREE'] = $quxiaocycode;
        $where['KIND'] = 1;
        $gaidong['MNEMONIC'] = " ";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 兼症常用选项下搜索框搜索证型
    public function jzajaxcysousuokuang()
    {
        $jzshurutj = I('post.jzshurutj');
        //接受传过来的输入的值
        $user = D("y_concomitancesypmptom");
        //判断是否为空
        if (!empty($jzshurutj)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $jzshurutj)) {
                $where['SPELL'] = array('like', "%{$jzshurutj}%");
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$jzshurutj}%");
            }
        }
        $where['KIND'] = 1;
        $where['MNEMONIC'] = 1;
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    // -----------------------------------------------------------兼症结束---------------
    // -----------------------------------------------------------舌象开始---------------
    //页面6 ajax 舌象下分类赋值
    public function yeliuAjaxshexiangdishexiangchs()
    {
        $mod = M();
        $shexiangzhcztj = I('post.shexiangzhcztj');
        //接受传过来的输入的值
        $sxjioj = trim($shexiangzhcztj);
        //去除最后的空格
        $sql = "select tree, CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,[CODE] FROM [y_concomitancesypmptom] WHERE code in (\r\nSELECT MIN(code) FROM [y_concomitancesypmptom] WHERE [TREE] LIKE '{$sxjioj}%' AND [KIND] = '2' group by tree) ";
        $shexiangdata = $mod->query($sql);
        if ($shexiangdata) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $shexiangdata));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $shexiangdata));
        }
    }
    //页面6 ajax 舌象分类其他按钮
    public function yeliuAjaxshexiangfenleiqueding()
    {
        $jzuser = D("y_concomitancesypmptom");
        $shexiangjswhere['KIND'] = 2;
        $shexiangjsdata = $jzuser->getBmInfoName($shexiangjswhere);
        $this->ajaxReturn($shexiangjsdata);
    }
    //页面6 ajax 舌象分类下搜索框搜索证型
    public function yeliuAjaxshexiangss()
    {
        $zxzfjg = I('post.zxzfjg');
        //接受传过来的输入的值
        $zhuzhengTree = I('post.zhuzhengTree');
        //接受传过来的主症tree号
        $dozhuzhengTree = trim($zhuzhengTree);
        //去除最后的空格
        $user = D("y_concomitancesypmptom");
        //模糊匹配符合主症的tree
        $where['TREE'] = array('like', "{$dozhuzhengTree}%");
        //判断是否是字母
        if (preg_match("/^[a-z]/i", $zxzfjg)) {
            $where['SPELL'] = array('like', "%{$zxzfjg}%");
        } else {
            $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$zxzfjg}%");
        }
        $where['KIND'] = 2;
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 舌象分类设为常用选择
    public function yeliuAjaxshexiangFensheweicyxx()
    {
        $shexiangfenlsheweicycode = I('post.shexiangfenlsheweicycode');
        //接受传过来的输入的值
        $user = M("y_concomitancesypmptom");
        $where['TREE'] = $shexiangfenlsheweicycode;
        $where['KIND'] = 2;
        $gaidong['MNEMONIC'] = "1";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 舌象检索下搜索框赋值
    public function yeliuAjaxshexiangjssousuofuzhi()
    {
        $user = D("y_concomitancesypmptom");
        $zxzfjg = I('post.zxzfjg');
        //接受传过来的输入的值
        //判断是否为空
        if (!empty($zxzfjg)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $zxzfjg)) {
                $where['SPELL'] = array('like', "%{$zxzfjg}%");
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$zxzfjg}%");
            }
        }
        $where['KIND'] = 2;
        $data = $user->getBmInfoName($where);
        // $aaa = $user->getLastSql();
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 舌象检索设为常用选择
    public function yeliuAjaxshexiangjssheweicyxx()
    {
        $jzjssheweicycode = I('post.jzjssheweicycode');
        //接受传过来的输入的值
        $user = M("y_concomitancesypmptom");
        $where['TREE'] = $jzjssheweicycode;
        $where['KIND'] = 2;
        $gaidong['MNEMONIC'] = "1";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 舌象常用选择中取消常用选择
    public function shexiangajaxcyQuXiancy()
    {
        $quxiaocycode = I('post.quxiaocycode');
        //接受传过来的输入的值
        $user = M("y_concomitancesypmptom");
        $where['TREE'] = $quxiaocycode;
        $where['KIND'] = 2;
        $gaidong['MNEMONIC'] = " ";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 舌象常用选项下搜索框搜索证型
    public function shexiangajaxcysousuokuang()
    {
        $jzshurutj = I('post.jzshurutj');
        //接受传过来的输入的值
        $user = D("y_concomitancesypmptom");
        //判断是否为空
        if (!empty($jzshurutj)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $jzshurutj)) {
                $where['SPELL'] = array('like', "%{$jzshurutj}%");
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$jzshurutj}%");
            }
        }
        $where['KIND'] = 2;
        $where['MNEMONIC'] = 1;
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    // -----------------------------------------------------------舌象结束---------------
    // -----------------------------------------------------------脉相开始---------------
    //页面6 ajax 脉象下分类赋值
    public function yeliuAjaxshexiangdimaixiangchs()
    {
        $shexiangzhcztj = I('post.shexiangzhcztj');
        //接受传过来的输入的值
        $sxjioj = trim($shexiangzhcztj);
        //去除最后的空格
        // $ajaxReturn($sxjioj);die;
        $user = D("y_concomitancesypmptom");
        $where['TREE'] = array('like', "{$sxjioj}%");
        $where['KIND'] = '3';
        $shexiangdata = $user->getBmInfoName($where);
        if ($shexiangdata) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $shexiangdata));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $shexiangdata));
        }
    }
    //页面6 ajax 脉象分类其他按钮
    public function yeliuAjaxmaixiangfenleiqueding()
    {
        $jzuser = D("y_concomitancesypmptom");
        $shexiangjswhere['KIND'] = 3;
        $shexiangjsdata = $jzuser->getBmInfoName($shexiangjswhere);
        $this->ajaxReturn($shexiangjsdata);
    }
    //页面6 ajax 脉象分类下搜索框搜索证型
    public function yeliuAjaxmaixiangss()
    {
        $zxzfjg = I('post.zxzfjg');
        //接受传过来的输入的值
        $zhuzhengTree = I('post.zhuzhengTree');
        //接受传过来的主症tree号
        $dozhuzhengTree = trim($zhuzhengTree);
        //去除最后的空格
        $user = D("y_concomitancesypmptom");
        //模糊匹配符合主症的tree
        $where['TREE'] = array('like', "{$dozhuzhengTree}%");
        //判断是否是字母
        if (preg_match("/^[a-z]/i", $zxzfjg)) {
            $where['SPELL'] = array('like', "%{$zxzfjg}%");
        } else {
            $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$zxzfjg}%");
        }
        $where['KIND'] = 3;
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 脉象分类设为常用选择
    public function yeliuAjaxmaixiangFensheweicyxx()
    {
        $maixiangfenlsheweicycode = I('post.maixiangfenlsheweicycode');
        //接受传过来的输入的值
        $user = M("y_concomitancesypmptom");
        $where['TREE'] = $maixiangfenlsheweicycode;
        $where['KIND'] = '3';
        $gaidong['MNEMONIC'] = "1";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 舌象检索下搜索框赋值
    public function yeliuAjaxmaixiangjssousuofuzhi()
    {
        $user = D("y_concomitancesypmptom");
        $zxzfjg = I('post.zxzfjg');
        //接受传过来的输入的值
        //判断是否为空
        if (!empty($zxzfjg)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $zxzfjg)) {
                $where['SPELL'] = array('like', "%{$zxzfjg}%");
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$zxzfjg}%");
            }
        }
        $where['KIND'] = 3;
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 舌象检索设为常用选择
    public function yeliuAjaxmaixiangjssheweicyxx()
    {
        $jzjsmaiweicycode = I('post.jzjsmaiweicycode');
        //接受传过来的输入的值
        $user = M("y_concomitancesypmptom");
        $where['TREE'] = $jzjsmaiweicycode;
        $where['KIND'] = 3;
        $gaidong['MNEMONIC'] = "1";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    //页面6 ajax 脉相常用选项下搜索框搜索证型
    public function maixiangajaxcysousuokuang()
    {
        $jzshurutj = I('post.jzshurutj');
        //接受传过来的输入的值
        $user = D("y_concomitancesypmptom");
        //判断是否为空
        if (!empty($jzshurutj)) {
            //判断是否是字母
            if (preg_match("/^[a-z]/i", $jzshurutj)) {
                $where['SPELL'] = array('like', "%{$jzshurutj}%");
            } else {
                $where['CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME))'] = array('like', "%{$jzshurutj}%");
            }
        }
        $where['KIND'] = 3;
        $where['MNEMONIC'] = 1;
        $data = $user->getBmInfoName($where);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //页面6 ajax 脉象常用选择中取消常用选择
    public function maixiangajaxcyQuXiancy()
    {
        $quxiaocycode = I('post.quxiaocycode');
        //接受传过来的输入的值
        $user = M("y_concomitancesypmptom");
        $where['TREE'] = $quxiaocycode;
        $where['KIND'] = 3;
        $gaidong['MNEMONIC'] = " ";
        $data = $user->where($where)->save($gaidong);
        if ($data) {
            $this->ajaxReturn("成功");
        } else {
            $this->ajaxReturn("哎呀，失败了");
        }
    }
    // -----------------------------------------------------------脉相结束---------------
    public function west()
    {
        $bqxx = M('bqxx');
        $where['BR_ID'] = $_SESSION['id'];
        $where['XH'] = session('xh');
        $lis = $bqxx->where($where)->field('xy_name')->select();
        $xyname = $lis[0]['xy_name'];
        $dict = M('xydrugcf_detial');
        $use = M('useage_table');
        $pl = M('usepl_table');
        // $xy = M('xy_name');
        // $xyname = $xy->select();
        $day = date('Ymd');
        $xydict = M('xydrugcf_detial');
        //获取当日处方最大的ID
        $where['cf_id'] = array('like', "{$day}%");
        $mustId = $xydict->where($where)->order("cf_id desc")->Field('cf_id')->find();
        $newId = $mustId['cf_id'] + 1;
        if ($newId == 1) {
            $newId = $day . '00001';
        }
        $where['cf_id'] = array('like', "{$day}%");
        $where['BR_ID'] = $_SESSION['id'];
        $where['XH'] = session('xh');
        $where['department'] = session('dpment');
        $list = $dict->where($where)->where('cf_flag<2')->distinct(true)->field('cf_id,xy_name')->select();
        // dump($list);die;
        $pllist = $pl->select();
        $useage = $use->select();
        $this->assign('newId', $newId);
        $this->assign('name', $list);
        $this->assign('useage', $useage);
        $this->assign('usepl', $pllist);
        // $this->assign('xyname',$xyname);
        //拿取个人病历号
        $xycfList = $dict->field("cf_id")->where("BR_ID='" . $_SESSION['id'] . "' and XH='" . session('xh') . "'")->group("cf_id")->select();
        $this->assign('xycfId', $xycfList);
        //西药处方号传递
        $xytms = count($xycfList) + 1;
        //单个病人的西药处方数量加一
        //获取当日处方最大的ID
        $where['cf_id'] = array('like', "{$day}%");
        $mustId = $dict->where($where)->order("cf_id desc")->Field('cf_id')->find();
        $newId = $mustId['cf_id'] + 1;
        if ($newId == 1) {
            $newId = $day . '00001';
        }
        $this->assign('xyname', $xyname);
        $this->assign("xytms", $xytms);
        //最新条目数
        $this->assign("maxId", $newId);
        /*
         * 杨旭亚
         * @2017 -05-27
         * 查看西药处方表有西药病名 显示西药病名 没有 显示 bqxx 表的 西药病名 （按照健康档案 的方式）
         *  */
        $xh = session('xh');
        $blh = session('id');
        //西药处置
        $xy2 = D('xydrugcf_detial');
        //            按照西药处方名称分组，分出有几类
        //条件
        $xyWhere['BR_ID'] = $blh;
        $xyWhere['XH'] = $xh;
        $xyWhere['cf_flag'] = array(array('eq', '1'), array('eq', '2'), 'or');
        //部门机构
        $xyWhere['department'] = session('dpment');
        $xyChuzhi = $xy2->getXyChuZhiInf($xyWhere);
        $xyChuzhiName = $xy2->getXyName($xyWhere);
                    //dump($xyChuzhiName);

        $xyNameXYB = '';
        //存放西医病名
        foreach ($xyChuzhiName as $key => $val) {
            if ($val['xy_name']) {
                $xyNameXYB = $val['xy_name'] . ";";
            }
        }
        $nameRes = substr($xyNameXYB, 0, -1);
        //去掉逗号后的西药名称
        if ($xyChuzhi) {
            $this->assign('xyflag', 1);
            //经过西药开方
            $this->assign('xyNameXYB', $nameRes);
            $this->assign('xyChuzhi', $xyChuzhi);
        }
        /*
         * 杨旭亚
         * @2017 -05-27
         * 查看西药处方表西药 的药名
         */
//        $yyxiyaoyaoming =  M('xydrugcf_detial')->where($xyWhere)
//            ->field('cf_id,yp_name,yp_spec,yp_total_amount,yp_useage,yp_yc_amount,yp_pl_day,yp_speci_use_name,fs')->order('cf_id')->select();
//        $this->assign('yyxiyaoyaoming',$yyxiyaoyaoming);
        //最新处方号传递
        $this->display();
    }
    public function zyhome()
    {
        $zhengX = $_GET['zhengxing'];
        $zhiZ = $_GET['zhize'];
        $pd = $_GET['id'];
        $cf = $_GET['pid'];
        $blh = session(id);
        $xh = session(xh);
        $pand = array();
        $name = array();
        $dw = array();
        $xw1 = array();
        //        $cfna =array();
        $sl = array();
        $prices = array();
        $yfs = array();
        $pri_data = array();
        $zynames = array();
        $bzs = array();
        $zzs = array();
        $dictyz = M('dict_order');
        $yz = $dictyz->field('order_name')->select();
        $this->assign('yz', $yz);
        $cf_dict = M('prescription');
        $cf_info = M('prescription_detail');
        $day = session('wh_userId') . date('ymd');
        //获取当日处方最大的ID
        $where['presc_no'] = array('like', "{$day}%");
        $mustId = $cf_info->where($where)->order("presc_no desc")->Field('presc_no')->find();
        //历史处方开始
        $blh = session(id);
        $xh = session(xh);
        // /获取所属机构
        $dpment = session('dpment');
        $chufang = M('Prescription');
        $historyCF = $chufang->join('station_p on Prescription.patient_id=station_p.br_id and Prescription.xh=station_p.xh')->field('presc_no,presc_name,convert(varchar(10),presc_date,120) presc_date,indicate,zy_name,bz,zz')->where("Prescription.patient_id='{$blh}' and station_p.department='{$dpment}' and station_p.jz_flag='2' and Prescription.xh<>'{$xh}'")->select();
//         echo $chufang->getLastSql();
        foreach ($historyCF as $key => $val) {
            if ($historyCF[$key]['indicate'] == 0) {
                $historyCF[$key]['indicate'] = '<span style="color:red;">未审</span>';
            } else {
                if ($historyCF[$key]['indicate'] == 1) {
                    $historyCF[$key]['indicate'] = '已审';
                } else {
                    if ($historyCF[$key]['indicate'] == 2) {
                        $historyCF[$key]['indicate'] = '已收费';
                    }
                }
            }
        }
        $this->assign('historyCF', $historyCF);
        //显示历史处方详细信息
        //历史处方结束
        //echo $cf_info->getLastSql();
        if ($mustId) {
            $newId = $mustId['presc_no'] + 1;
        } else {
            $newId = $day . '00001';
        }
        unset($where);
        //echo $newId;
        //表 pri_detai 公共数据
        $pri_data['patient_id'] = (string) $_SESSION[id];
        $pri_data['presc_date'] = date("Y-m-d H:i:s");
        $pri_data['operator'] = (string) $_SESSION[wh_userName];
        $pri_data['pregnant_woman'] = 0;
        $pri_data['dose'] = 7;
        $pri_data['order_text'] = '';
        $pri_data['doctor_id'] = $_SESSION[wh_userId];
        $pri_data['indicate'] = 0;
        $pri_data['xh'] = $_SESSION[xh];
        $xh = session(xh);
        // $day = date('ymd');
        $whe['presc_no'] = array('like', "{$day}%");
        $whe['patient_id'] = $_SESSION['id'];
        $whe['xh'] = $xh;
        $whe['department'] = session('dpment');
        $dic = D('prescription');
        $lis = $dic->showZy($whe);
        // echo $lis;die;
        $this->assign('cfName', $lis);
        $jian = M('dict_usage');
        $listj = $jian->field('name')->select();
        $this->assign(jianlist, $listj);
        $model = D('YMakenature');
        //性味归经
        $three = $model->getAll("tree like '0000.00_' and isshow=0");
        $jm = $model->getAll("tree like '0000.001.___' and isshow=0");
        $xw = $model->getAll("tree like '0000.002.___' and isshow=0");
        $gj = $model->getAll("tree like '0000.003.___' and isshow=0");
//        @杨旭亚
//        @显示后台添加的归经（所填药品的归经不是库里归经）
        $domodel = M();
          $dogj = $domodel->query("select CODE,TREE,convert(varchar(max),DECRYPTBYKEY(name)) as name  from y_makenature where TREE like '0000.003%' AND isshow=1
        and len(TREE)<17 ORDER BY TREE DESC");
//        dump($gj);die;
        $jmyp = $model->getAll("tree like '0000.001.%' and isshow=1");
        $xwyp = $model->getAll("tree like '0000.002.%' and isshow=1");
        // echo $model->getLastSql();die;
        $gjml = $model->getAll("tree like '0000.003.00_.%' and isshow=0");

        $gjyp = $model->getAll("tree like '0000.003.00_.___.%' and isshow=1");
        foreach ($jmyp as &$v) {
            $v['qev'] = substr($v['tree'], 0, 12);
        }
        foreach ($xwyp as &$v) {
            $v['qev'] = substr($v['tree'], 0, 12);
        }
        foreach ($gjml as &$v) {
            $v['qev'] = substr($v['tree'], 0, 12);
        }
        foreach ($dogj as &$v) {
            $v['qev'] = substr($v['tree'], 0, 12);
        }
//        dump($dogj);die;
        foreach ($gjyp as &$v) {
            $v['qev'] = substr($v['tree'], 0, 16);
        }
        $this->assign("jm", $jm);
        $this->assign("xw", $xw);
        $this->assign("gj", $gj);
//        dump($dogj);die;
        $this->assign("dogj", $dogj);
        $this->assign("jmyp", $jmyp);
        $this->assign("xwyp", $xwyp);
        $this->assign("gjml", $gjml);
        $this->assign("gjyp", $gjyp);
        $this->assign('historyCF', $historyCF);
        $this->display('Kaifang/zyUp');
    }
    public function ajaxWord()
    {
        $id = I('post.id');
        $word = myProcedure($id);
        $this->ajaxReturn($word);
    }
    function apds()
    {
        $where['department'] = session('dpment');
        $model = M('drug_dict');
        $result = $model->where("drug_code='{$_POST['code']}'")->where($where)->select();
        $dict = M('y_makenature');
        $tree = $dict->where("code='{$_POST['code']}'")->field('tree')->select();
        $result[0]['tree'] = $tree[0]['tree'];
        $this->ajaxReturn($result);
    }
    /**
     * 双击历史处方，保存成为当天处方
     */
    public function saveToday()
    {
        $cfh = I('get.cfh');
        echo $cfh;
    }
    function bhhome()
    {
        $preno = (string) $_GET[presc_no];
        $model = M('prescription');
        $data['indicate'] = '4';
        $model->where("presc_no='{$_GET['presc_no']}'")->save($data);
        $this->redirect('Kaifang/zyhome', 0);
    }
    // public function backDict(){
    //     // $id =$_GET['presc_no'];
    //     $id = '17032900030';
    //     $dict = M('prescription_detail');
    //     $where['prescription_detail.presc_no'] = $id;
    //     $list = $dict->where($where)->join('prescription on prescription_detail.presc_no=prescription.presc_no')->field('prescription.presc_name,prescription_detail.presc_no as cfid,prescription_detail.drug_code,prescription_detail.drug_name,prescription_detail.amount,prescription_detail.drug_units')->select();
    //     $list[0]['presc_no'] = $id;
    //     $this->assign('bohui',$list);
    //     $this->display('Kaifang/zyhome');
    // }
    //
    //
    public function jieShouChuFang()
    {
        $zhengX = $_GET['zhengxing'];
        $zhiZ = $_GET['zhize'];
        $pd = $_GET['id'];
        $cf = $_GET['pid'];
//        dump($cf);die;
        $blh = session(id);
        $xh = session(xh);
        $pand = array();
        $name = array();
        $dw = array();
        $xw1 = array();
        $sl = array();
        $prices = array();
        $yfs = array();
        $pri_data = array();
        $zynames = array();
        $bzs = array();
        $zzs = array();
        $dictyz = M('dict_order');
        $yz = $dictyz->field('order_name')->select();
        $this->assign('yz', $yz);
        $cf_dict = M('prescription');
        $cf_info = M('prescription_detail');
        $day = session('wh_userId') . date('ymd');
        //获取当日处方最大的ID
        $where['presc_no'] = array('like', "{$day}%");
        $mustId = $cf_info->where($where)->order("presc_no desc")->Field('presc_no')->find();
        //历史处方开始
        $blh = session(id);
        $xh = session(xh);
        $chufang = M('Prescription');
        $historyCF = $chufang->join('station_p on Prescription.patient_id=station_p.br_id and Prescription.xh=station_p.xh')->field('presc_no,presc_name,convert(varchar(10),presc_date,120) presc_date,indicate,zy_name,bz,zz')->where("Prescription.patient_id='{$blh}' and Prescription.indicate=1 and station_p.jz_flag='2' and Prescription.xh<>'{$xh}'")->select();
        foreach ($historyCF as $key => $val) {
            if ($historyCF[$key]['indicate'] == 0) {
                $historyCF[$key]['indicate'] = '<span style="color:red;">未审</span>';
            } else {
                if ($historyCF[$key]['indicate'] == 1) {
                    $historyCF[$key]['indicate'] = '已审';
                } else {
                    if ($historyCF[$key]['indicate'] == 2) {
                        $historyCF[$key]['indicate'] = '已收费';
                    }
                }
            }
        }
        $this->assign('historyCF', $historyCF);
        //显示历史处方详细信息
        //历史处方结束
        //性味归经开始
        $model = D('YMakenature');
        //性味归经
        $three = $model->getAll("tree like '0000.00_' and isshow=0");
        $jm = $model->getAll("tree like '0000.001.___' and isshow=0");
        $xw = $model->getAll("tree like '0000.002.___' and isshow=0");
        $gj = $model->getAll("tree like '0000.003.___' and isshow=0");
        $dogj = $model->query("select CODE,TREE,convert(varchar(max),DECRYPTBYKEY(name)) as name  from y_makenature where TREE like '0000.003%' AND isshow=1
        and len(TREE)=12 ORDER BY TREE DESC");
//        dump($dogj);die;
        $jmyp = $model->getAll("tree like '0000.001.%' and isshow=1");
        $xwyp = $model->getAll2("y_makenature.tree like '0000.002.%' and isshow=1");
        $gjml = $model->getAll("tree like '0000.003.00_.%' and isshow=0");
        $gjyp = $model->getAll("tree like '0000.003.00_.___.%' and isshow=1");
        foreach ($jmyp as &$v) {
            $v['qev'] = substr($v['tree'], 0, 12);
        }
        foreach ($xwyp as &$v) {
            $v['qev'] = substr($v['tree'], 0, 12);
        }
        foreach ($gjml as &$v) {
            $v['qev'] = substr($v['tree'], 0, 12);
        }
        foreach ($gjyp as &$v) {
            $v['qev'] = substr($v['tree'], 0, 16);
        }
        //性味归经结束
//        echo $cf_info->getLastSql();
        if ($mustId) {
            $newId = $mustId['presc_no'] + 1;
        } else {
            $newId = $day . '00001';
        }
        // dump($xwyp);die;
        unset($where);
        //echo $newId;
        //表 pri_detai 公共数据
        $pri_data['patient_id'] = (string) $_SESSION[id];
        $pri_data['presc_date'] = date("Y-m-d H:i:s");
        $pri_data['operator'] = (string) $_SESSION[wh_userName];
        $pri_data['pregnant_woman'] = 0;
        $pri_data['dose'] = 7;
        $pri_data['order_text'] = '';
        $pri_data['doctor_id'] = $_SESSION[wh_userId];
        $pri_data['indicate'] = 0;
        $pri_data['xh'] = $_SESSION[xh];
        switch ($pd) {
            //病名 症型 诊疗指南开方合并
            case '1':
                $dict = D('bz_cf');
                $cf_tree = explode(' ', $cf);
                array_pop($cf_tree);
                array_shift($cf_tree);
                $num = count($cf_tree);
                for ($i = 0; $i < $num; $i++) {
                    $where['bz_cf.cfdm'] = $cf_tree[$i];
                    $new[$i] = $dict->Meihb($where, $cf_tree[$i]);
                    // echo $dict->getLastSql();
                }
                for ($j = 0; $j < count($new); $j++) {
                    for ($q = 0; $q < count($new[$j]); $q++) {
                        if (!in_array($new[$j][$q]['drug_code'], $pand)) {
                            array_push($pand, $new[$j][$q]['drug_code']);
                            array_push($name, $new[$j][$q]['drug_name']);
                            array_push($dw, $new[$j][$q]['dw']);
                            array_push($xw1, $new[$j][$q]['xw1']);
                            //                            array_push($cfna,$new[$j][$q]['cf_name']);
                            array_push($sl, $new[$j][$q]['sl']);
                            array_push($prices, $new[$j][$q]['price']);
                            array_push($yfs, $new[$j][$q]['yf']);
                            if (!in_array($new[$j][$q]['zyname'], $zynames)) {
                                array_push($zynames, $new[$j][$q]['zyname']);
                                $pri_data['zy_name'] = $new[$j][$q]['zyname'];
                            }
                            if (!in_array($new[$j][$q]['zx'], $bzs)) {
                                array_push($bzs, $new[$j][$q]['zx']);
                                $pri_data['bz'] = implode("|", $bzs);
                            }
                            if (!in_array($new[$j][$q]['zf'], $zzs)) {
                                array_push($zzs, $new[$j][$q]['zf']);
                                $pri_data['zz'] = implode("|", $zzs);
                            }
                        }
                        // array_push($name,$data[$j][$q]['drug_name']);
                    }
                }
                for ($a = 0; $a < count($pand); $a++) {
                    $data[$a]['presc_no'] = $newId;
                    $data[$a]['drug_code'] = $pand[$a];
                    $data[$a]['drug_name'] = $name[$a];
                    $data[$a]['drug_units'] = $dw[$a];
                    //                    $data[$a]['cf_name'] = $cfna[$a];
                    $data[$a]['xw1'] = $xw1[$a];
                    $data[$a]['amount'] = $sl[$a];
                    $data[$a]['price'] = $prices[$a];
                    $data[$a]['usage'] = $yfs[$a];
                    $data[$a]['costs'] = $sl[$a] * $prices[$a];
                    //Prescription表数据
                    $pri_data['costs'] += $data[$a]['costs'];
                }
                // dump($data);die;
                foreach ($data as $key => $val) {
                    $val['drug_no'] = $key + 1;
                    $val['department'] = session('dpment');
                    $cf_info->data($val)->add();
                }
                $pri_data['presc_no'] = (string) $newId;
                $pri_data['cf_tree'] = (string) $cf;
                $pri_data['presc_name'] = '合并处方';
                $pri_data['usage1'] = '口服';
                $pri_data['decoction'] = '水煎';
                $pri_data['dosage'] = '1/日';
                $pri_data['cf_tree'] = $cf;
                $dic = D('prescription');
                $pri_data['department'] = session('dpment');
                $cf_dict->data($pri_data)->add();
                $xh = session(xh);
                $whe['presc_no'] = array('like', "{$day}%");
                $whe['patient_id'] = $_SESSION['id'];
                $whe['xh'] = $xh;
                $whe['department'] = session('dpment');
                $lis = $dic->showZy($whe);
                // echo $lis;die;
                $this->assign('cfName', $lis);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $this->assign("jm", $jm);
                $this->assign("xw", $xw);
                $this->assign("gj", $gj);
                $this->assign("dogj", $dogj);
                $this->assign("jmyp", $jmyp);
                $this->assign("xwyp", $xwyp);
                $this->assign("gjml", $gjml);
                $this->assign("gjyp", $gjyp);
                $this->assign('historyCF', $historyCF);
                $this->display('Kaifang/zyUp');
                break;
                //病名 症型 诊疗指南开方不合并
            //病名 症型 诊疗指南开方不合并
            case '2':
                $dict = D('bz_cf');
                $cf_tree = explode(' ', $cf);

                array_pop($cf_tree);
                array_shift($cf_tree);
                $num = count($cf_tree);
                for ($i = 0; $i < $num; $i++) {

                    $where['bz_cf.cfdm'] = $cf_tree[$i];
                    $res = $dict->Meihb($where, $cf_tree[$i]);
                    foreach ($res as $key => $val) {
                        $data['presc_no'] = $newId;
                        $data['drug_no'] = $key + 1;
                        $data['drug_code'] = $val['drug_code'];
                        $data['drug_name'] = $val['drug_name'];
                        $data['drug_units'] = $val['dw'];
                        $data['xw1'] = $val['xw1'];
                        $data['amount'] = $val['sl'];
                        $data['price'] = $val['price'];
                        $data['usage'] = $val['yf'];
                        $data['costs'] = $val['sl'] * $val['price'];
                        $pri_data['costs'] += $data['costs'];
                        $data['department'] = session('dpment');
                        $data['drug_no'] = $val['serial_no'];
                        $cf_info->data($data)->add();
                        //                        echo $cf_info->getLastSql();
                        $pri_data['presc_no'] = (string) $newId;
//                        $pri_data['cf_tree'] = (string) $cf;
                        $pri_data['cf_tree'] = $cf_tree["$i"];
                        $pri_data['presc_name'] = $val['cf_name'];
                        $pri_data['usage1'] = '口服';
                        $pri_data['decoction'] = '水煎';
                        $pri_data['dosage'] = '1/日';
                        $pri_data['zy_name'] = $val['zyname'];
                        $pri_data['bz'] = $val['zx'];
                        $pri_data['zz'] = $val['zf'];
                    }
                    $pri_data['department'] = session('dpment');
//                    dump($pri_data);die;
                    $cf_dict->data($pri_data)->add();
                    $newId++;
                }
                $dic = D('prescription');
                $xh = session(xh);
                $whe['presc_no'] = array('like', "{$day}%");
                $whe['patient_id'] = $_SESSION['id'];
                $whe['xh'] = $xh;
                $whe['department'] = session('dpment');
                $lis = $dic->showZy($whe);
                $this->assign('cfName', $lis);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $this->assign("jm", $jm);
                $this->assign("xw", $xw);
                $this->assign("gj", $gj);
                $this->assign("dogj", $dogj);
                $this->assign("jmyp", $jmyp);
                $this->assign("xwyp", $xwyp);
                $this->assign("gjml", $gjml);
                $this->assign("gjyp", $gjyp);
                $this->assign('historyCF', $historyCF);
                $this->display('Kaifang/zyUp');
                break;
                //经典方
            //经典方
            case '3':
                $dict = D('y_recipemain');
                $res = $dict->getNew($cf);
                // var_dump($res);die;
                foreach ($res as $key => $val) {
                    $data['presc_no'] = $newId;
                    $data['drug_no'] = $key + 1;
                    $data['drug_code'] = $val['drug_code'];
                    $data['drug_name'] = $val['drug_name'];
                    $data['drug_units'] = $val['dw'];
                    $data['xw1'] = $val['xw1'];
                    $data['amount'] = $val['sl'];
                    $data['price'] = $val['price'];
                    $data['usage'] = $val['yf'];
                    $data['costs'] = $val['sl'] * $val['price'];
                    $pri_data['costs'] += $data['costs'];
                    $data['drug_no'] = $val['serial_no'];
                    $data['department'] = session('dpment');
                    $cf_info->data($data)->add();
                    $pri_data['presc_no'] = (string) $newId;
                    $pri_data['cf_tree'] = (string) $cf;
                    $pri_data['presc_name'] = $val['cf_name'];
                    $pri_data['usage1'] = '口服';
                    $pri_data['decoction'] = '水煎';
                    $pri_data['dosage'] = '1/日';
                    $pri_data['zy_name'] = '';
                    $pri_data['bz'] = '';
                    $pri_data['zz'] = '';
                }
                $pri_data['department'] = session('dpment');
                $cf_dict->data($pri_data)->add();
                $dic = D('prescription');
                $xh = session(xh);
                $whe['presc_no'] = array('like', "{$day}%");
                $whe['patient_id'] = $_SESSION['id'];
                $whe['xh'] = $xh;
                $whe['department'] = session('dpment');
                $lis = $dic->showZy($whe);
                $this->assign('cfName', $lis);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $this->assign("jm", $jm);
                $this->assign("xw", $xw);
                $this->assign("gj", $gj);
                $this->assign("dogj", $dogj);
                $this->assign("jmyp", $jmyp);
                $this->assign("xwyp", $xwyp);
                $this->assign("gjml", $gjml);
                $this->assign("gjyp", $gjyp);
                $this->assign('historyCF', $historyCF);
                $this->display('Kaifang/zyUp');
                break;
            case '4':
                $cf = $_GET[pid];
                $id = (int) $cf;
                $dict = M('experience');
                $dpment = session('dpment');
                $where['department'] = $dpment;
                $res = $dict->where("experience.id={$id}")->where($where)->join("experience_detail on experience_detail.id=experience.id")->join("drug_dict on drug_dict.drug_code=experience_detail.drug_code")->field('experience_detail.id as ids,drug_dict.drug_code,drug_dict.drug_name,drug_dict.price,experience.name as cf_name,experience_detail.amount as sl,experience_detail.drug_units as dw,experience_detail.usage,drug_dict.xw1')->select();
                foreach ($res as $key => $val) {
                    $data['presc_no'] = $newId;
                    $data['drug_no'] = $key + 1;
                    $data['drug_code'] = $val['drug_code'];
                    $data['drug_name'] = $val['drug_name'];
                    $data['drug_units'] = $val['dw'];
                    $data['xw1'] = $val['xw1'];
                    $data['amount'] = $val['sl'];
                    $data['price'] = $val['price'];
                    $data['usage'] = $val['usage'];
                    $data['costs'] = $val['sl'] * $val['price'];
                    // $data['drug_no'] = $val['serial_no'];
                    $pri_data['costs'] += $data['costs'];
                    $data['department'] = session('dpment');
                    $cf_info->data($data)->add();
                    $pri_data['presc_no'] = (string) $newId;
                    $pri_data['cf_tree'] = (string) $cf;
                    $pri_data['presc_name'] = $val['cf_name'];
                    $pri_data['usage1'] = '口服';
                    $pri_data['decoction'] = '水煎';
                    $pri_data['dosage'] = '1/日';
                    $pri_data['zy_name'] = '';
                    $pri_data['bz'] = '';
                    $pri_data['zz'] = '';
                }
                $pri_data['department'] = session('dpment');
                $cf_dict->data($pri_data)->add();
                $dic = D('prescription');
                $xh = session(xh);
                $whe['presc_no'] = array('like', "{$day}%");
                $whe['patient_id'] = $_SESSION['id'];
                $whe['xh'] = $xh;
                $whe['department'] = session('dpment');
                $lis = $dic->showZy($whe);
                $this->assign('cfName', $lis);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $this->assign("jm", $jm);
                $this->assign("xw", $xw);
                $this->assign("gj", $gj);
                $this->assign("dogj", $dogj);
                $this->assign("jmyp", $jmyp);
                $this->assign("xwyp", $xwyp);
                $this->assign("gjml", $gjml);
                $this->assign("gjyp", $gjyp);
                $this->assign('historyCF', $historyCF);
                $this->display('Kaifang/zyUp');
                break;
            //辨证开方合并
            case '5':
                $cf_tree = explode(' ', $cf);
                array_pop($cf_tree);
                array_shift($cf_tree);
                $num = count($cf_tree);
                $dict = D('y_recipemain');
                for ($i = 0; $i < $num; $i++) {
                    // $where['bz_cf.cfdm'] = $cf_tree[$i];
                    $new[$i] = $dict->getNew($cf_tree[$i]);
                }
                //var_dump($new);
                for ($j = 0; $j < count($new); $j++) {
                    for ($q = 0; $q < count($new[$j]); $q++) {
                        if (in_array($new[$j][$q]['drug_code'], $pand)) {
                        } else {
                            array_push($pand, $new[$j][$q]['drug_code']);
                            array_push($name, $new[$j][$q]['drug_name']);
                            array_push($dw, $new[$j][$q]['dw']);
                            array_push($xw1, $new[$j][$q]['xw1']);
                            //array_push($cfna,$new[$j][$q]['cf_name']);
                            array_push($sl, $new[$j][$q]['sl']);
                            array_push($prices, $new[$j][$q]['price']);
                            array_push($yfs, $new[$j][$q]['yf']);
                        }
                        // array_push($name,$data[$j][$q]['drug_name']);
                    }
                }
                for ($a = 0; $a < count($pand); $a++) {
                    $data[$a]['presc_no'] = $newId;
                    $data[$a]['drug_code'] = $pand[$a];
                    $data[$a]['drug_name'] = $name[$a];
                    $data[$a]['drug_units'] = $dw[$a];
                    //                    $data[$a]['cf_name'] = $cfna[$a];
                    $data[$a]['xw1'] = $xw1[$a];
                    $data[$a]['amount'] = $sl[$a];
                    $data[$a]['price'] = $prices[$a];
                    $data[$a]['usage'] = $yfs[$a];
                    $data[$a]['costs'] = $sl[$a] * $prices[$a];
                    //Prescription表数据
                    $pri_data['costs'] += $data[$a]['costs'];
                }
                // var_dump($data);
                foreach ($data as $key => $val) {
                    $val['drug_no'] = $key + 1;
                    $val['department'] = session('dpment');
                    $cf_info->data($val)->add();
                }
                $pri_data['presc_no'] = (string) $newId;
                $pri_data['cf_tree'] = (string) $cf;
                $pri_data['presc_name'] = '合并处方';
                $pri_data['usage1'] = '口服';
                $pri_data['decoction'] = '水煎';
                $pri_data['dosage'] = '1/日';
                $pri_data['department'] = session('dpment');
                $cf_dict->data($pri_data)->add();
                $dic = D('prescription');
                $xh = session(xh);
                $whe['presc_no'] = array('like', "{$day}%");
                $whe['patient_id'] = $_SESSION['id'];
                $whe['xh'] = $xh;
                $whe['department'] = session('dpment');
                $lis = $dic->showZy($whe);
                $this->assign('cfName', $lis);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $this->assign("jm", $jm);
                $this->assign("xw", $xw);
                $this->assign("gj", $gj);
                $this->assign("dogj", $dogj);
                $this->assign("jmyp", $jmyp);
                $this->assign("xwyp", $xwyp);
                $this->assign("gjml", $gjml);
                $this->assign("gjyp", $gjyp);
                $this->assign('historyCF', $historyCF);
                $this->display('Kaifang/zyUp');
                break;
            case '6':
                $cf_tree = explode(' ', $cf);
                array_pop($cf_tree);
                array_shift($cf_tree);
                $num = count($cf_tree);
                $dict = D('y_recipemain');
                for ($i = 0; $i < $num; $i++) {
                    // $where['bz_cf.cfdm'] = $cf_tree[$i];
                    $res = $dict->getNew($cf_tree[$i]);
                    foreach ($res as $key => $val) {
                        $data['presc_no'] = $newId;
                        $data['drug_no'] = $key + 1;
                        $data['drug_code'] = $val['drug_code'];
                        $data['drug_name'] = $val['drug_name'];
                        $data['drug_units'] = $val['dw'];
                        $data['xw1'] = $val['xw1'];
                        $data['amount'] = $val['sl'];
                        $data['price'] = $val['price'];
                        $data['usage'] = $val['yf'];
                        $data['drug_no'] = $val['serial_no'];
                        $data['costs'] = $val['sl'] * $val['price'];
                        $pri_data['costs'] += $data['costs'];
                        $data['department'] = session('dpment');
                        $cf_info->data($data)->add();
                        //                        echo $cf_info->getLastSql();
                        $pri_data['presc_no'] = (string) $newId;
                        $pri_data['cf_tree'] = (string) $cf;
                        $pri_data['presc_name'] = $val['cf_name'];
                        $pri_data['usage1'] = '口服';
                        $pri_data['decoction'] = '水煎';
                        $pri_data['dosage'] = '1/日';
                        $pri_data['zy_name'] = '';
                        $pri_data['bz'] = '';
                        $pri_data['zz'] = '';
                    }
                    $pri_data['department'] = session('dpment');
                    $cf_dict->data($pri_data)->add();
                    $newId++;
                }
                $dic = D('prescription');
                $xh = session(xh);
                $whe['presc_no'] = array('like', "{$day}%");
                $whe['patient_id'] = $_SESSION['id'];
                $whe['xh'] = $xh;
                $whe['department'] = session('dpment');
                $lis = $dic->showZy($whe);
                $this->assign('cfName', $lis);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $jian = M('dict_usage');
                $listj = $jian->field('name')->select();
                $this->assign(jianlist, $listj);
                $this->assign("jm", $jm);
                $this->assign("xw", $xw);
                $this->assign("gj", $gj);
                $this->assign("dogj", $dogj);
                $this->assign("jmyp", $jmyp);
                $this->assign("xwyp", $xwyp);
                $this->assign("gjml", $gjml);
                $this->assign("gjyp", $gjyp);
                $this->assign('historyCF', $historyCF);
                $this->display('Kaifang/zyUp');
                break;
        }
    }
}