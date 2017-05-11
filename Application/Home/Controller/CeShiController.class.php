<?php
namespace Home\Controller;
use Think\Controller;
use Org\Util\AjaxPage;
class CeShiController extends Controller {
    //改变处方药品
    public function index(){
    	
    		
    		import("ORG.Util.AjaxPage");// 导入分页类 注意导入的是自己写的AjaxPage类
			$credit = M('y_concomitancesypmptom');
			 $jzjswhere['KIND'] = 1;
			$count = $credit->where($jzjswhere)->count(); //计算记录数
			$limitRows = 5; // 设置每页记录数
			$p = new AjaxPage($count, $limitRows,"index"); //第三个参数是你需要调用换页的ajax函数名
			$limit_value = $p->firstRow . "," . $p->listRows;
			$data = $credit->where($jzjswhere)->limit($limit_value)->field("TREE,CONVERT(VARCHAR(MAX),DECRYPTBYKEY(NAME)) as NAME")->select(); // 查询数据
			$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
			$this->assign('list',$data);
			$this->assign('page',$page);
			$this->display();
    	
			
	}
}