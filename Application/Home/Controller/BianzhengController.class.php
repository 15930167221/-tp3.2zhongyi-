<?php
namespace Home\Controller;
use Think\Controller;
class BianzhengController extends PublicController {
    protected function __initialize()
    {
        parent::_initialize();
    }
    //获取页面传的兼症tree号
    public function jianzhengtree(){
    	$zhuzTree = trim(I('post.zhuzTree'));
    	$jianzhengtree = I('post.bianzhengjztreehao');//获取所有兼症
    	$jianzhengtreequchukong = trim($jianzhengtree);//去除最后的空格
    	$zuizhongjianzhengtree = explode(' ',$jianzhengtreequchukong);//以空格区分开每一个兼症的tree
    		//dump($zuizhongjianzhengtree);die;
    		$arrTree = array();
    		array_push($arrTree,$zhuzTree);
 
	        $result=array_uintersect($zuizhongjianzhengtree,$arrTree,"myfunction");

	        $mod = M();
			if (count($result) > 0) {
				$where = implode(',',$result);
				$wheres = str_replace(",", "','", $where);
				$sql = "select zx_name,zx_tree,round(sum(fs),4) fs,ze_name,ze_tree,bz_name,bz_tree from(
					select CONVERT(VARCHAR(MAX),DECRYPTBYKEY(e.name)) zx_name,
					a.memo zx_tree,
					case when isnull(num1,0)=0 or (select sum(isnull(num1,0)) from Y_CONCOMITANCESYPMPTOM b where b.memo=a.memo and kind='1') =0 then 
							1/(select count(*) from Y_CONCOMITANCESYPMPTOM b where b.memo=a.memo and kind='1')
						 else isnull(num1,0)/(select sum(isnull(num1,0)) from Y_CONCOMITANCESYPMPTOM b where b.memo=a.memo and kind='1')
					end fs,
					CONVERT(VARCHAR(MAX),DECRYPTBYKEY(c.name)) ze_name,
					c.tree ze_tree,
					CONVERT(VARCHAR(MAX),DECRYPTBYKEY(d.name)) bz_name,
					d.tree bz_tree
					from Y_CONCOMITANCESYPMPTOM a
					join Y_TREATPRINCIPIA c on substring(c.tree,1,14)= a.memo--治则
					left join Y_ASSISTANT d on  substring(d.tree,1,19)=c.tree--备注
					left join y_semiotics e on e.tree= a.memo--症型名称
					where a.tree in('$wheres')
					) aa
					group by zx_name,zx_tree,ze_name,ze_tree,bz_name,bz_tree
					order by fs desc ,zx_tree,ze_tree,bz_tree
					";
				$res = $mod->query($sql);
				//echo $mod->getLastSql();
				//var_dump($res);die;
			} else {
				$sql = "select zx_name,zx_tree,round(sum(fs),4) fs,ze_name,ze_tree,bz_name,bz_tree from(
					select CONVERT(VARCHAR(MAX),DECRYPTBYKEY(e.name)) zx_name,
					a.memo zx_tree,
					-1 fs,
					CONVERT(VARCHAR(MAX),DECRYPTBYKEY(c.name)) ze_name,
					c.tree ze_tree,
					CONVERT(VARCHAR(MAX),DECRYPTBYKEY(d.name)) bz_name,
					d.tree bz_tree
					from Y_CONCOMITANCESYPMPTOM a
					join Y_TREATPRINCIPIA c on substring(c.tree,1,14)= a.memo--治则
					left join Y_ASSISTANT d on  substring(d.tree,1,19)=c.tree--备注
					left join y_semiotics e on e.tree= a.memo--症型名称
					where a.tree in(select tree from Y_CONCOMITANCESYPMPTOM where substring(tree,1,9)  =   '$zhuzTree')
					) aa
					group by zx_name,zx_tree,ze_name,ze_tree,bz_name,bz_tree order by fs desc ,zx_tree,ze_tree,bz_tree";
				$res = $mod->query($sql);
                //echo $mod->getLastSql();
			}
    	$this->ajaxReturn($res);
    }
    //改变处方名
    public function huoqucftree(){
    	$bz_treetiaoj = I('post.bz_treetiaoj');//备注的tree
    	$zhiz_treetiaoj = I('post.zhiz_treetiaoj');//治则的tree
    	$cftreeuser = M('y_recipemain');
    	//拼接where条件
    	//判断备注是否存在
    	if ($bz_treetiaoj == "null") {
    		//模糊匹配治则的前几位
    		$zftreewhere['TREE'] = array('like',"{$zhiz_treetiaoj}%"); 
    	}else{
    		//模糊匹配治则的前几位和备注的前几位
    		$zftreewhere['TREE'] = array('like',"{$zhiz_treetiaoj}%"); 
    		//截取备注的tree的前24位
    		$dobztree = substr($bz_treetiaoj, 0,24);
    		$zftreewhere['TREE'] = array('like',"{$dobztree}%"); 
    	}
    		$zftreewhere['ISSHOW'] = '1';

        $cftreedata = $cftreeuser->where($zftreewhere)->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,tree,EXPLAIN,SOURCE,EFFICACY,MAINCURE,TSYF")->select();

    	$this->ajaxReturn($cftreedata);
    }
    //改变处方药品药解
    public function cfyaopinming(){
        $mod = M();
        $tjyouzhengxing = I('post.tjyouzhengxing');//接受ajax传过来的条件
        // /获取所属机构
        $dpment = session('dpment');
        $sql = "select  dict_drug_zy.drug_name,bz_cf.dw,bz_cf.sl,isnull((select top 1 name from dict_usage where code=bz_cf.yf),bz_cf.yf) as yf,bz_cf.serial_no FROM [bz_cf] 
INNER JOIN dict_drug_zy on dict_drug_zy.drug_code=bz_cf.ypdm  
WHERE bz_cf.cfdm = '$tjyouzhengxing' and dict_drug_zy.department = '$dpment' order by serial_no";
        $res = $mod->query($sql);
        $this->ajaxReturn($res);
    }
    //方解
    public function cfyaopinmingfangjie(){
        $tjyouzhengxing = I('post.tjyouzhengxing');//接受ajax传过来的条件
        $user = M("y_recipemain");
        $where['TREE'] = $tjyouzhengxing;
        $dataf = $user->where($where)->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,CODE,SPELL,TREE,EXPLAIN,SOURCE,EFFICACY,MAINCURE,TSYF")->select();
        $this->ajaxReturn($dataf);
    }
    //主症下的检索
    public function zhuzjiansuo(){
        $user = D("y_mainsypmpotm");
        $jswhere['ISSHOW'] = 1;
        $data = $user->getBmInfoName($jswhere);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //主症下的常用选择
    public function zhuzchangyxz(){
        $user = D("y_mainsypmpotm");
        $dowhere['ISSHOW'] = 1;
        $dowhere['MNEMONIC'] = 1;
        $data = $user->getBmInfoName($dowhere);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    /**
     * 兼症分类其他
     */
    public function yeliujianzfenleiquta(){
        $rect = M('y_concomitancesypmptom');
        $jzjswhere['KIND'] = 1;
        $data =  $rect->where($jzjswhere)->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME, TREE")->order('tree')->select();
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //兼症的检索
    public function jianzjiansuo(){
         $zhcztj = I('post.zhcztj');//接受传过来的输入的值
        $jioj = trim($zhcztj);//去除最后的空格
        $rect = M('y_concomitancesypmptom');
        $jzjswhere['TREE'] = array('like',"{$jioj}%");
        $jzjswhere['KIND'] = 1;
        $data =  $rect->where($jzjswhere)->field("CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME, TREE")->order('tree')->select();
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //获取兼症的常用选择
    public function jianzchangyxz(){
        
        $jzuser = D("y_concomitancesypmptom");
        $jzcyxxwhere['MNEMONIC'] = 1;
        $jzcyxxwhere['KIND'] = 1;
        $data = $jzuser->getBmInfoName($jzcyxxwhere);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    ////舌象的检索
    public function shexiangjs(){
        $jzuser = D("y_concomitancesypmptom");
        $shexiangjswhere['KIND'] = 2;
        $data = $jzuser->getBmInfoName($shexiangjswhere);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    ////舌象的常用选择
    public function shexiangchangyxz(){
        $mod = M();
        $sql = "select tree, CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME,[CODE] FROM [y_concomitancesypmptom] WHERE code in (
SELECT MIN(code) FROM [y_concomitancesypmptom] WHERE [KIND] = '2' AND [MNEMONIC] = '1' group by tree) ";
        $data = $mod->query($sql);
        
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //脉相检索
    public function maixiangjs(){
        
        $jzuser = D("y_concomitancesypmptom");
        $maixiangjswhere['KIND'] = 3;
        $data = $jzuser->getBmInfoName($maixiangjswhere);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
    //脉相常用选择
    public function maixiangchangyxz(){
        
        $jzuser = D("y_concomitancesypmptom");
        $maixiangcyxxwhere['MNEMONIC'] = 1;
        $maixiangcyxxwhere['KIND'] = 3;
        $data = $jzuser->getBmInfoName($maixiangcyxxwhere);
        if ($data) {
            $this->ajaxReturn(array('msg' => '查询成功', 'res' => $data));
        } else {
            $this->ajaxReturn(array('msg' => '查询失败', 'res' => $data));
        }
    }
}