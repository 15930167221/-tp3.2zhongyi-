<?php
namespace Home\Controller;

use Think\Easemob;
class IndexController extends PublicController
{
    protected function __initialize()
    {
        parent::_initialize();
    }
    public function home($rev)
    {
        $res = M()->field('b1.id as id1,b1.department,b2.id as id2,b2.levelid,b2.pid')->table("user_info_dict as b1,about as b2")->where("b1.id=" . session(wh_userId) . "and b1.department=b2.id")->find();
        $this->assign('res', $res);
        $this->assign('rev', $rev);
        $user = M('station_p');
        //查询条件
        $condition['br_id'] = session('id');
        $condition['xh'] = session('xh');
        $condition['department'] = session('dpment');
        $natian = session('natian');
        // var_dump($condition['br_id']);
        // var_dump($natian);
        $userInf = $user->field('br_id,br_name,xh,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nls,nl,convert(varchar(19),p_date,120) as p_date')->where($condition)->find();
        //        $userInf=$user->query("select br_id,br_name,xh,xb,nl,convert(varchar(19),p_date,120) as p_date from station_p where br_id='".session('id')."'' and xh=".session('xh'));
        $this->assign('natian', $natian);
        $this->assign('user', $userInf);
        $this->assign('days', session('days'));
        $this->display();
    }
    //用药比例
    public function blView()
    {
        $code1 = $_GET['code'];
        $wrph1 = $_GET['wrph'];
        $name1 = $_GET['name'];
        $code = explode(',', $code1);
        $wrph = explode(',', $wrph1);
        $name = explode(',', $name1);
        $this->assign('code', $code);
        $this->assign('name', $name);
        $this->assign('wrph', $wrph);
        $this->display('Kaifang/blView');
    }
    //接诊区
    public function jiezhen()
    {
        // echo strtotime('2017-06-17 12:00:00');die;
        // 法一自己写的附带样式
        $rect = M('station_p');
        // 区分是否是接诊区页面
        $cid = I('get.cid');
        $this->assign('qufenjiez', $cid);
        $tjri = date('Y-m-d', time());
        //获取当天日期
        $where['p_date'] = array('between', "{$tjri} 00:00:00,{$tjri} 23:59:59");
        $where['jz_flag'] = 1;
        //获取所属机构
        $dpment = session('dpment');
        $where['department'] = $dpment;
        $count = $rect->where($where)->count();
        // 查询满足要求的总记录数 $map表示查询条件
        $page = getpage($count, 9);
        //控制页面显示条数
        $show = $page->show();
        // 分页显示输出
        $this->assign('page', $show);
        // 赋值分页输出
        //以上是分页 ， 以下是数据
        //'jz_flag=1'
        $data = $rect->where($where)->order('isnull(jz_date,p_date) asc')->limit($page->firstRow . ',' . $page->listRows)->field('convert(varchar(19),isnull(jz_date,p_date),120) as p_date,convert(varchar(19),jz_date,120) as jz_date,convert(varchar(19),cs_date,120) as cs_date,br_name,xb, dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),isnull(jz_date,p_date),120)) as nl,nl as nlt,tel,br_id,xh')->select();
        //查询数据（未完成就诊的）$Page->firstRow 起始条数 $Page->listRows 获取多少条
        //         echo $rect->getLastSql();
        // 获取登记传过来的id
        $djid = I('get.djid');
        $djxh = I('get.djxh');
        if (!empty($djid)) {
            $this->assign('dataid', $djid);
            // 赋值模板变量
            $this->assign('dataxh', $djxh);
            // 赋值模板变量
        }
        $this->assign('data', $data);
        // 赋值模板变量
        $this->display();
    }
    // 接诊区详情 ajax接诊详情参数
    public function jiezhenxiangqajax()
    {
        $id = I('get.id');
        $xh = I('get.xh');
        $user = M('station_p');
        $where['br_id'] = $id;
        $where['xh'] = $xh;
        $data = $user->where($where)->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,tel,br_id,xh,dw,pass,e_mail,ghf,fax,convert(varchar(10),cs_date,120) as cs_date')->select();
        // dump($data);die;
        $this->ajaxReturn($data, 'json');
    }
    // 接诊区修改 ajax参数
    public function jiezhenxiugaiajax()
    {
        $id = I('get.id');
        $xh = I('get.xh');
        $user = M('station_p');
        // 实例化Data数据模型
        $where['br_id'] = $id;
        $where['xh'] = $xh;
        $data = $user->where($where)->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,tel,br_id,xh,dw,pass,e_mail,ghf,fax,convert(varchar(10),cs_date,120) as cs_date')->select();
        // dump($data);die;
        $this->ajaxReturn($data, 'json');
    }
    //执行修改 接诊区
    public function dojiezhenajaxxiugai()
    {
        $id = I('post.br_id');
        //修改条件
        $xh = I('post.xh');
        //修改条件
        $user = I('post.');
        //修改信息
        $data = M('station_p');
        // 实例化Data数据模型
        $where['br_id'] = $id;
        $where['xh'] = $xh;
        $data->where($where)->save($user);
        //执行修改
        $this->redirect("Index/jiezhen");
        //重定向
    }
    //点击存session  接诊区
    public function saveSession()
    {
        session('id', I('post.brid'));
        session('xh', I('post.brxh'));
        session('natian', I('post.natian'));
        session('tzbsInfo', null);
        session('jkdaSave', null);
        session('save', null);
    }
    //患者登记
    public function dengji()
    {
        session('tzbsInfo', null);
        session('jkdaSave', null);
        //判断是否保存过健康档案
        session('save', null);
        // 判断病例号id是否存在（存在是从查询处跳转过来的）
        $getid = I('get.id');
        $xh = I('get.xh');
        //获取所属机构
        $dpment = session('dpment');
        if (!empty($getid)) {
            $user = M('station_p');
            // 实例化Data数据模型
            $data = $user->where("br_id='{$getid}' and xh='{$xh}' and department='{$dpment}'")->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),isnull(jz_date,p_date),120)) as nl,tel,br_id,xh,dw,pass,e_mail,ghf,fax,convert(varchar(10),cs_date,120) as cs_date')->select();
            // dump($data);die;
            $this->assign('data', $data);
            //设置编号
            // dump($data);die;
        } else {
            $user = M('station_p');
            // 实例化Data数据模型
            //获取所属机构
            $dpment = session('dpment');
            //获取医生id
            $wh_userId = session('wh_userId');
            $where['operator'] = $wh_userId;
            $where['department'] = $dpment;
            $br_id = $user->where($where)->field('br_id')->select();
            foreach ($br_id as $v) {
                foreach ($v as $value) {
                    $qb[] = substr($value, 0, 8);
                }
            }
            $a = array_count_values($qb);
            //计算出现次数
            $b = $a[date('Ymd')];
            if (!empty($b)) {
                //判断数值在哪个范围
                if ($b < 9) {
                    $d = $b + 1;
                    // 规定格式前面加几个0
                    $c = $wh_userId . "0000" . $d;
                    // dump($c);die;
                } else {
                    if ($b >= 9 && $b < 99) {
                        $d = $b + 1;
                        $c = $wh_userId . "000" . $d;
                    } else {
                        if ($b >= 99 && $b < 999) {
                            $d = $b + 1;
                            $c = $wh_userId . "00" . $d;
                        } else {
                            if ($b >= 999 && $b < 9999) {
                                $d = $b + 1;
                                $c = $wh_userId . "0" . $d;
                            }
                        }
                    }
                }
                // $c = $b+1;
                $br_id = date('Ymd') . $c;
                // dump($br_id);die;
            } else {
                $br_id = date('Ymd') . $wh_userId . "00001";
            }
            $data[0]['br_id'] = $br_id;
            $this->assign('data', $data);
            //设置编号
        }
        //拼接错误信息
        $cwxinxi = I('get.cwxinxi');
        $this->assign('cwxinxi', $cwxinxi);
        //设置编号
        $this->display();
    }
    //患者保存
    public function hzbc()
    {
        // dump($uid);die;
        $br_name = I('post.br_name');
        $xb = I('post.xb');
        $ghf = I('post.ghf');
        //判断挂号费
        if (empty($br_name)) {
            //重定向到登记
            $this->redirect('Index/dengji', array('cwxinxi' => "姓名未填写"));
            die;
        } else {
            if (empty($xb)) {
                //重定向到登记
                $this->redirect('Index/dengji', array('cwxinxi' => "性别未填写"));
                die;
            } else {
                // 获取所属机构
                $dpment = session('dpment');
                $station = M('station_p');
                //判断是否是复诊
                $br_id = I('post.br_id');
                $where['br_id'] = $br_id;
                $where['department'] = $dpment;
                // 查出就诊几次
                $pdfuzhen = $station->where($where)->count();
                // 加一为当前就诊次数
                $dangqingjiuzhengcishu = $pdfuzhen + 1;
                // dump($dangqingjiuzhengcishu);die;
                $data = I('post.');
                //获取数据
                // dump($data);die;
                $data['xh'] = $dangqingjiuzhengcishu;
                //获取登入人id  uid
                $uid = session('wh_userId');
                $data['operator'] = $uid;
                $data['department'] = $dpment;
                $station->add($data);
                //添加数据
                $this->redirect('Index/jiezhen', array("djid" => $br_id, "djxh" => $dangqingjiuzhengcishu));
                //重定向到接诊区
            }
        }
    }
    //患者同名
    public function ajaxtongming()
    {
        $name = I('post.name');
        $user = M('station_p');
        $where['br_name'] = $name;
        //获取所属机构
        $dpment = session('dpment');
        $where['department'] = $dpment;
        $data = $user->where($where)->field('convert(varchar(19),p_date,120) as p_date,convert(varchar(19),jz_date,120) as jz_date,convert(varchar(10),cs_date,120) as cs_date,br_name,xb, dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),isnull(jz_date,p_date),120)) as nl,tel,br_id,xh,pass,dw,fax,e_mail')->select();
        $this->ajaxReturn($data);
    }
    //患者预约
    public function yuyue()
    {
        //左侧病历号
        $getid = I('get.id');
        $xh = I('get.xh');
        $user = M('station_p');
        // 实例化Data数据模型
        //获取所属机构
        $dpment = session('dpment');
        if (!empty($getid)) {
            $datazuo = $user->where("br_id='{$getid}' and xh='{$xh}' and department = '{$dpment}'")->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,tel,br_id,xh,dw,pass,e_mail,ghf,fax,convert(varchar(10),cs_date,120) as cs_date')->select();
            $this->assign('datazuo', $datazuo);
            //设置编号
            // dump($data);die;
        } else {
            //获取所属机构
            $dpment = session('dpment');
            //获取医生id
            $wh_userId = session('wh_userId');
            $where['operator'] = $wh_userId;
            $where['department'] = $dpment;
            $br_id = $user->where($where)->field('br_id')->select();
            foreach ($br_id as $v) {
                foreach ($v as $value) {
                    $qb[] = substr($value, 0, 8);
                }
            }
            $a = array_count_values($qb);
            //计算出现次数
            $b = $a[date('Ymd')];
            if (!empty($b)) {
                //判断数值在哪个范围
                if ($b < 9) {
                    $d = $b + 1;
                    // 规定格式前面加几个0
                    $c = $wh_userId . "0000" . $d;
                    // dump($c);die;
                } else {
                    if ($b >= 9 && $b < 99) {
                        $d = $b + 1;
                        $c = $wh_userId . "000" . $d;
                    } else {
                        if ($b >= 99 && $b < 999) {
                            $d = $b + 1;
                            $c = $wh_userId . "00" . $d;
                        } else {
                            if ($b >= 999 && $b < 9999) {
                                $d = $b + 1;
                                $c = $wh_userId . "0" . $d;
                            }
                        }
                    }
                }
                // $c = $b+1;
                $br_id = date('Ymd') . $c;
                // dump($br_id);die;
            } else {
                $br_id = date('Ymd') . $wh_userId . "00001";
            }
            $datazuo[0]['br_id'] = $br_id;
            // dump($datazuo);die;
            $this->assign('datazuo', $datazuo);
            //设置编号
        }
        // $this->assign('data',$data);// 赋值数据集
        // 右侧预约情况
        // 获取当前时间
        $yuyuedtime = date("Y-m-d");
        //拼接最后条件
        $where['p_date'] = array('between', "{$yuyuedtime} 00:00:00,{$yuyuedtime} 23:59:59");
        //获取数据->where("p_date like '". $times."%'")"reserve=2 and p_date like '".$yuyuedtime."%' "
        $where["reserve"] = 2;
        $where["jz_flag"] = 1;
        $dpment = session('dpment');
        $where['department'] = $dpment;
        // $map['p_date'] = array('like',$yuyuedtime.'%');
        $data = $user->where($where)->field('convert(varchar(19),p_date,120) as p_date,br_name')->order('p_date desc')->select();
        // dump($shuju);die;
        $this->assign('data', $data);
        //拼接错误信息
        $cwxinxi = I('get.cwxinxi');
        $this->assign('cwxinxi', $cwxinxi);
        $this->display();
    }
    //患者查询
    public function chaxun()
    {
        $qufenbiaodan = I('get.qufenbiaodan');
        // 区分是否是表单
        if ($qufenbiaodan == 1) {
            //链接数据库
            $rect = M('station_p');
            // 获取其他信息 拼接where条件
            $suoyoutj = I('get.');
            $br_name = I('get.br_name');
            $br_id = I('get.br_id');
            // var_dump($suoyoutj);
            foreach ($suoyoutj as $k => $v) {
                //当键值等于开始时间 不算入where条件内
                if ($k != "p_datekai") {
                    //当键值等于终止时间 不算入where条件内
                    if ($k != "p_datezhong") {
                        if ($k != "br_name") {
                            if ($k != "br_id") {
                                //判断值不为空
                                if (!empty($v)) {
                                    $a[] = $k;
                                    $b[] = $v;
                                }
                            }
                        }
                    }
                }
            }
            //把值存在的数据 整合成一个数组 （建对应值的一个数组）
            $com = array_combine($a, $b);
            // dump($com);
            //病例号不存在   获取时间拼接where条件
            $p_datekai = I('get.p_datekai');
            //获取开始日期
            $p_datezhong = I('get.p_datezhong');
            //获取终止日期
            // dump($p_datezhong);
            if (!empty($p_datezhong)) {
                if (!empty($p_datekai)) {
                    //拼接最后条件
                    $com['p_date'] = array('between', "{$p_datekai} 00:00:00,{$p_datezhong} 23:59:59");
                }
            }
            $com['br_name'] = array('like', "{$br_name}%");
            $com['br_id'] = array('like', "{$br_id}%");
            $dpment = session('dpment');
            $com['department'] = $dpment;
            // dump($com);die;
            $count = $rect->where($com)->count();
            // 查询满足要求的总记录数 $map表示查询条件
            $page = getpage($count, 8);
            //控制页面显示条数
            if (empty($suoyoutj)) {
                foreach ($suoyoutj as $key => $val) {
                    $page->parameter[$key] = urlencode($val);
                }
            }
            $show = $page->show();
            // 分页显示输出
            $this->assign('page', $show);
            // 赋值分页输出
            //以上是分页 ， 以下是数据
            $data = $rect->where($com)->order('p_date desc')->limit($page->firstRow . ',' . $page->listRows)->field('convert(varchar(19),p_date,120) as p_date,convert(varchar(19),jz_date,120) as jz_date,br_name,xb,nl,tel,br_id,xh,dw,pass,e_mail,ghf,fax,jz_flag,convert(varchar(19),cs_date,120) as cs_date,department')->select();
            // echo $rect->getLastSql();die;
            $this->assign('data', $data);
            $this->assign('tjymxx', $suoyoutj);
            $this->display();
        } else {
            $this->display();
        }
    }
    //查询详细信息
    public function chaxunxiangxi()
    {
        $id = I('get.id');
        $xh = I('get.xh');
        $dp = I('get.dp');
        $user = M('station_p');
        $where['br_id'] = $id;
        $where['xh'] = $xh;
        $where['department'] = $dp;
        $data = $user->where($where)->field('convert(varchar(19),p_date,120) as p_date,br_name,xb,dbo.get_age(convert(varchar(19),cs_date,120),convert(varchar(19),jz_date,120)) as nl,tel,br_id,xh,dw,pass,e_mail,ghf,fax,convert(varchar(10),cs_date,120) as cs_date')->select();
        $this->ajaxReturn($data, 'json');
    }
    //健康档案
    /**
     *
     */
    public function jiankang()
    {
        //        echo session('id');
        //接受从患者登记处传值（post方式）
        $station = D('station_p');
        //链接数据库
        if (IS_POST) {
            $br_name = I('post.br_name');
            //姓名
            $xb = I('post.xb');
            //性别
            $ghf = I('post.ghf');
            //挂号费
            //判断挂号费
            if (empty($br_name)) {
                //重定向到登记
                $this->redirect('Index/dengji', array('cwxinxi' => "姓名未填写"));
                die;
            } else {
                if (empty($xb)) {
                    //重定向到登记
                    $this->redirect('Index/dengji', array('cwxinxi' => "性别未填写"));
                    die;
                } else {
                    // 获取所属机构
                    $dpment = session('dpment');
                    //判断是否是复诊
                    $br_id = I('post.br_id');
                    $where['br_id'] = $br_id;
                    $where['department'] = $dpment;
                    // 查出就诊几次
                    $pdfuzhen = $station->where($where)->count();
                    // 加一为当前就诊次数
                    $dangqingjiuzhengcishu = $pdfuzhen + 1;
                    $data = I('post.');
                    //获取数据
                    $data['xh'] = $dangqingjiuzhengcishu;
                    //获取登入人id  uid
                    $uid = session('wh_userId');
                    $data['operator'] = $uid;
                    $data['department'] = $dpment;
                    //插入接诊时间
                    $jz_date = date('Y-m-d H:i:s', time());
                    $data['jz_date'] = $jz_date;
                    // dump($data);die;
                    $station->add($data);
                    //添加数据
                    session(id, $br_id);
                    //设置病历号存入session
                    session(xh, $dangqingjiuzhengcishu);
                    //设置序号存入session
                }
            }
            //接受接诊区传值(get方式)
        } else {
            if (IS_GET) {
                // 判断get.id是否存在
                if (isset($_GET['id'])) {
                    $id = I('get.id');
                    //获取条件
                    $xh = I('get.xh');
                    session(id, $id);
                    //设置编号存入session
                    session(xh, $xh);
                    //设置编号存入session
                    //判断患者的接诊日期是否为空
                    $wherejzdate['br_id'] = $id;
                    $wherejzdate['xh'] = $xh;
                    $wherejzdate['department'] = session('dpment');
                    $userjzdate = $station->where($wherejzdate)->field('jz_date')->select();
                    //如果接诊时间是空  改成当前时间否则不变
                    if ($userjzdate['0']['jz_date'] == null) {
                        // 将接诊区时间改为当前时间
                        //插入接诊时间
                        $jz_date = date('Y-m-d H:i:s', time());
                        $data['jz_date'] = $jz_date;
                        $station->where($wherejzdate)->data($data)->save();
                    }
                }
            }
        }
        $blh = session('id');
        $xh = session('xh');
        //        echo $xh;
        //如果患者已经进行登记
        if ($blh && $xh) {
            $whereTj['br_id'] = $blh;
            $whereTj['xh'] = $xh;
            $whereTj['department'] = session('dpment');
            $userInf = $station->getMoreUserInf($whereTj);
            //$userInf=$station->where("br_id=$blh and xh=$xh")->select();//查询出患者信息
            //将时间中的时分秒去掉
            $userInf[0]['p_date'] = substr($userInf[0]['p_date'], 0, 10);
            $userInf[0]['cs_date'] = substr($userInf[0]['cs_date'], 0, 10);
            $userInf[0]['jz_date'] = substr($userInf[0]['jz_date'], 0, 19);
            $this->assign('data', $userInf);
            //将患者信息传递到前端界面
            //获取患者的体制信息
            $tz = session('tzbsInfo');
            $tzzd = array_slice($tz, 36);
            //患者的体质
            $tzStr = implode(",", $tzzd);
            //将体制信息转化为字符串
            $this->assign('tzzd', $tzStr);
            //判断是否有保存数据
            $jkda = D('jkda_xx');
            //实例化健康档案选项信息表
            $bqxx = D('bqxx');
            //如果通过历史就诊记录传值
            if (I('get.jid') && I('get.jxh')) {
                $blh = I('get.jid');
                $xh = I('get.jxh');
                $this->assign('lijzjl', 1);
                //判断历史就诊记录弹框
                $this->assign('lijzxh', $xh);
                //历史就诊记录序号
            }
            //查询条件
            $whereConf['br_id'] = $blh;
            $whereConf['xh'] = $xh;
            $jkdaConf = $jkda->getOneInf($whereConf);
            $whereConf1['BR_ID'] = $blh;
            $whereConf1['XH'] = $xh;
            //王昊
            $jkdaa = M('jkda_xx');
            $wher['BR_ID'] = $blh;
            $xh1 = $xh - 1;
            $ll = $jkdaa->query("select jws,gms,jws_date,crbs,crbs_date from jkda_xx where xh={$xh1} and BR_ID = '{$blh}'");
            // dump($ll);die;
            $gms = $ll[0]['gms'];
            $jws = $ll[0]['jws'];//既往史
            $jws_date = $ll[0]['jws_date'];
            $crbs = $ll[0]['crbs'];//传染病史
            $crbs_date = $ll[0]['crbs_date'];
            if (is_null($jkdaConf)) {
                $jkdaConf['gms'] = $gms;
                $jkdaConf['jws'] = $jws;
                $jkdaConf['jws_date'] = $jws_date;
                $jkdaConf['crbs'] = $crbs;
                $jkdaConf['crbs_date'] = $crbs_date;
            } else {
                if ($jkdaConf['jws'] == '') {
                    $jkdaConf['jws'] = $jws;
                    $jkdaConf['jws_date'] = $jws_date;
                }
                if ($jkdaConf['crbs'] == '') {
                    $jkdaConf['crbs'] = $crbs;
                    $jkdaConf['crbs_date'] = $crbs_date;
                }
                if ($jkdaConf['gms'] == '') {
                    $jkdaConf['gms'] = $gms;
                }
            }
            // dump($jkdaConf);die;
            $this->assign('jk1', $jkdaConf);
            //王昊
            $bqxxConf = $bqxx->getOneBqxxInf($whereConf1);
//             dump($jkdaConf);die;
            if ($jkdaConf && $bqxxConf) {
                if ($jkdaConf['jws_date'] == '1900-01-01') {
                    $jkdaConf['jws_date'] = '';
                }
                if ($jkdaConf['crbs_date'] == '1900-01-01') {
                    $jkdaConf['crbs_date'] = '';
                }
                $this->assign('jk1', $jkdaConf);
                //将患者的选项信息传回页面
                $this->assign('jk2', $bqxxConf);
                //将患者的病名信息传回页面
            }
            //如果患者存在历史记录(历史完成就诊记录信息)
            //历史记录条件
            $hisCond['bqxx.BR_ID'] = $blh;
            $hisCond['station_p.jz_flag'] = 2;
            $hisCond['bqxx.XH'] != $xh;
            $his = $bqxx->getHis($hisCond);
            $this->assign('his', $his);
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
            //            dump($xyChuzhi);
            $xyChuzhiName = $xy2->getXyName($xyWhere);
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
            //中药处置
            $zy2 = M('Prescription');
            $zyWhere['patient_id'] = $blh;
            $zyWhere['xh'] = $xh;
            $zyWhere['indicate'] = array(array('eq', '1'), array('eq', '2'), 'or');
            //部门机构
            $zyWhere['department'] = session('dpment');
            //处置信息
            $zyChuzhi=$zy2->where($zyWhere)->select();
//            echo $zy2->getLastSql();
            //辨证信息和治则信息
            $zyBzAll='';//存放辨证
            $zyZzAll='';//存放治则
            foreach($zyChuzhi as $key=> $val){
                if($val['bz']){
                    $zyBzAll.=$val['bz'].";";
                    $zyZzAll.=$val['zz'].";";
                }
            }
            $zyBzAll2 = substr($zyBzAll, 0, -1);
            //去掉逗号后的多余符号
            $zyZzAll2 = substr($zyZzAll, 0, -1);
            //去掉逗号后的多余符号
            //处置信息中的中医病名
            $zyChuzhiName = $zy2->where($zyWhere)->field('zy_name')->group('zy_name')->select();
            $zyChuzhiNameAll = '';
            //存放中医病名
            foreach ($zyChuzhiName as $key => $val) {
                if ($val['zy_name']) {
                    $zyChuzhiNameAll .= $val['zy_name'] . ";";
                }
            }
            $zyChuzhiNameAll2 = substr($zyChuzhiNameAll, 0, -1);
            //去掉逗号后的多余符号
            $bqxx = M('bqxx');
            $wh['BR_ID'] = $_SESSION['id'];
            $wh['XH'] = session('xh');
            $lis = $bqxx->where($wh)->field('zy_name')->select();
            $zyname = $lis[0]['zy_name'];
            if ($zyname == NULL || $zyname == '') {
                $zyChuzhiNameAll2 = $zyChuzhiNameAll2;
            } else {
                $zyChuzhiNameAll2 = $zyname;
            }
            
            if($zyChuzhi){
                $this->assign('zyflag',1);//经过中药开方
                $this->assign('zyChuzhiNameAll2',$zyChuzhiNameAll2);//中医病名
                $this->assign('zyBzAll2',$zyBzAll2);//辨证
                $this->assign('zyZzAll2',$zyZzAll2);//治则
                $this->assign('zyChuzhi',$zyChuzhi);
            }
            /*
             * @杨旭亚
             * 2017-06-02
             * 健康档案读取收费项目显示到页面
             * */
            $usershoufeixiangm = M('g_outp_bill_item');
            $whereshoufeixiangm['CLINIC_NUM'] = $blh;
            $whereshoufeixiangm['xh'] = $xh;
            $whereshoufeixiangm['DEPARTMENT'] = session('dpment');
            $whereshoufeixiangm['BILL_STATUS'] = 1;
            $rest = $usershoufeixiangm->where($whereshoufeixiangm)->field('ITEM_CODE,UNIT_PRICE,convert(varchar(10),amount,120) as amount,UNITS,TOTAL')->select();
//            dump($rest);
//            var_dump($usershoufeixiangm->getLastSql());die;
            $this->assign('shoufeixiangm',$rest);
        }
        //以下不管患者是否登记数据都存在
        /** typeId=
         * 1：既往病史；2：传染病史；3：过敏史；4：忘神；5：忘色；6：体态；
         * 7：体型；8：质量；9：时间；10：食欲；11：口味；12：大便便次；13：便质；
         * 14：小便便次；15：便色；16：性情；17：性格；18：舌色；19：舌体；20：动态；
         * 21：苔质；22：苔色；23：脉诊；
         */
        $bl = D('jkda_bl');
        //健康档案病历表
        $bls = $bl->getBlInf();
        $this->assign('bls', $bls);
        //西医病名
        //        $xy=D('xy_name');
        //        $xyName=$xy->getXyName();
        //        $this->assign('xyName',$xyName);
        //        //中医病名
        //        $zy=D('tcd_zybm');
        //        $zyName=$zy->getZyBm();
        //        $this->assign('zyName',$zyName);
        $this->assign('jkSave', session('jkdaSave'));
        $this->assign('cz', session('cz'));
        $this->display();
    }
    /**
     * 点击健康档案的保存按钮将患者的病例信息分别存放到jkda_xx和bqxx表中
     * 如果已经存在该患者的相关信息则提示，是否进行信息更新
     */
    public function jiankangSave()
    {
        session('cz', 0);
        //健康档案处置判断
        //session('xyFlag',0);//判断健康档案西医诊断的来源，0：bqxx表，1：西药处方表。
        //        $wcjz=I("get.wcjz"); //完成就诊判断，1点击保存，2点击完成就诊
        $userJKInf = I('post.');
        //病人健康档案信息
        $jkda = D('jkda_xx');
        //实例化健康档案选项信息表
        $bqxx = D('bqxx');
        $user = M('station_p');
        //获取患者的体制信息
        $tz = session(tzbsInf);
        $tzzd = array_slice($tz, 36);
        //患者的体质
        $tzStr = implode(",", $tzzd);
        //将体制信息转化为字符串
        $userJKInf['tzzd'] = $tzStr;
        //体质信息赋值
        $userJKInf['userids'] = session(wh_userId);
        //就诊医生id
        $data = array();
        $data['br_id'] = session(id);
        $data['xh'] = session(xh);
        //组合bqxx表
        $data1 = array();
        $data1['BR_ID'] = session(id);
        $data1['XH'] = session(xh);
        $data1['userids'] = session(wh_userId);
        //就诊医生id
        $data1['LUNZHI'] = $userJKInf['zybz'];
        $data1['LUNZHI_SM'] = $userJKInf['zyzz'];
        $data1['zy_name'] = $userJKInf['zyzd'];
        $data1['xy_name'] = $userJKInf['xyzd'];
        $data1['cz'] = $userJKInf['cz'];
        $riqi = $user->field("jz_date")->where("br_id='" . session(id) . "' and xh='" . session(xh) . "'")->find();
        $data1['jz_date'] = $riqi['jz_date'];
//        dump($data1);die;
        //        $data1['jz_date']=date("Y-m-d H:i:s");
        //组合bqxx表结束
        //判断患者是否登记
        if ($data['br_id'] && $data['xh']) {
            $jkdaInf = array_merge($data, $userJKInf);
            //将患者的id和xh与患者的选项信息合并
            $whereConf['BR_ID'] = $data['br_id'];
            $whereConf['XH'] = $data['xh'];
            $jkdaConf = $jkda->where($data)->select();
            $bqxxConf = $bqxx->where($whereConf)->select();
            //判断患者是否进行过保存
            if ($jkdaConf || $bqxxConf) {
                session('jkdaSave', 1);
                //判断是否有保存数据
                $jkdaChange = $jkda->where($data)->save($jkdaInf);
                //更新到jkda_xx表
                $bqxxChange = $bqxx->where($whereConf)->save($data1);
                //更新到bqxx表
                $userInf = $user->where("BR_ID='" . $data['br_id'] . "'")->save($userJKInf);
                if ($jkdaChange || $bqxxChange || $userInf) {
                    $this->success('保存成功！', U('Index/jiankang'), 1);
                } else {
                    $this->error('保存失败！', U('Index/jiankang'), 1);
                }
            } else {
                session('jkdaSave', 1);
                //判断是否有保存数据
                //添加数据
                $jkdaSave = $jkda->add($jkdaInf);
                $jkdaSave2 = $bqxx->add($data1);
                $userInf = $user->where("BR_ID='" . $data['br_id'] . "'")->save($userJKInf);
                if ($jkdaSave && $jkdaSave2 || $userInf) {
                    $this->success('保存成功！', U('Index/jiankang'), 1);
                } else {
                    $this->error('保存失败！', U('Index/jiankang'), 1);
                }
            }
        } else {
            $this->error('您还没有登记，请您先去登记！', U('Index/dengji'), 3);
        }
    }
    //    体质辨识答题界面
    public function tizhi()
    {
        $blh = session('id');
        $xh = session('xh');
        //读取界面
        $ti = D('tz_question')->getQuestionInf();
        //题目数组切割成为3*11
        $ti1 = array_slice($ti, 0, 11);
        $ti2 = array_slice($ti, 11, 11);
        $ti3 = array_slice($ti, 22, 11);
        $this->assign('ti1', $ti1);
        //1到11题序号
        $this->assign('ti2', $ti2);
        //12到22题序号
        $this->assign('ti3', $ti3);
        //23到33题序号
        $this->assign('ti', $ti);
        //界面读取结束
        //判断保存按钮是否能用
        $this->assign(flag, session('flag'));
        $this->assign(save, session('save'));
        $tzbs = I("get.");
        $a = 0;
        foreach ($tzbs as $key => $val) {
            if ($key != 'tzjg8' || $key == '0') {
                $a++;
            } else {
                $len = $a + 1;
                //组合切割字符串的位置
            }
        }
        //判断患者是否登记
        if ($blh && $xh) {
            $userWhere['id'] = $xh;
            $userWhere['bianhao'] = $blh;
            $userInf = D('tz_jbxx')->getUserAns($userWhere);
            //患者的答题记录表,患者的选项信息
            $data = D('tz_jieguo')->getUserInfAndAnsJG($userWhere);
            //患者的个人信息和答题结果
            //            判断患者是否存在保存答题信息
            if ($userInf && $data) {
                $this->assign('dati', 1);
                //不需要判断答题数目
                if ($tzbs) {
                    //有历史纪录又有传递过来的记录则传递过来的记录优先
                    //将传过来的数据分成两个数组
                    $res1 = array_slice($tzbs, 0, $len);
                    $res2 = array_slice($tzbs, $len);
                    //                    print_r($res1);
                    $this->assign('res1', $res1);
                    $this->assign('baoj', $res2);
                    $this->display();
                } else {
                    //有历史纪录但是没有提交的则显示历史纪录
                    //日期去掉时分秒
                    $data['jz_date'] = substr($data['jz_date'], 0, 10);
                    $this->assign('res1', $data);
                    //患者的答题结果
                    $this->assign('userCheckedInf', $userInf);
                    //患者的个人信息和答案信息
                    //体质辨识结果生成部分
                    $tz = array();
                    if ($data[tzjg] != '否') {
                        $tz[] = $data[tzname] . "-" . $data[tzjg];
                    }
                    if ($data[tzjg1] != '否') {
                        $tz[] = $data[tzname1] . "-" . $data[tzjg1];
                    }
                    if ($data[tzjg2] != '否') {
                        $tz[] = $data[tzname2] . "-" . $data[tzjg2];
                    }
                    if ($data[tzjg3] != '否') {
                        $tz[] = $data[tzname3] . "-" . $data[tzjg3];
                    }
                    if ($data[tzjg4] != '否') {
                        $tz[] = $data[tzname4] . "-" . $data[tzjg4];
                    }
                    if ($data[tzjg5] != '否') {
                        $tz[] = $data[tzname5] . "-" . $data[tzjg5];
                    }
                    if ($data[tzjg6] != '否') {
                        $tz[] = $data[tzname6] . "-" . $data[tzjg6];
                    }
                    if ($data[tzjg7] != '否') {
                        $tz[] = $data[tzname7] . "-" . $data[tzjg7];
                    }
                    if ($data[tzjg8] != '否') {
                        $tz[] = $data[tzname8] . "-" . $data[tzjg8];
                    }
                    //$tz结果内容
                    $this->assign('baoj', $tz);
                    $this->display();
                }
            } else {
                $this->assign('dati', 0);
                //需要判断答题数目
                //没有保存的历史记录
                //将传过来的数据分成两个数组
                $res1 = array_slice($tzbs, 0, $len);
                $res2 = array_slice($tzbs, $len);
                //              print_r($tzbs);
                $this->assign('res1', $res1);
                $this->assign('baoj', $res2);
                $this->display();
            }
        } else {
            //患者未进行登记显示空界面
            $this->display();
        }
    }
    /**
     * 答题完成生成相应的答题结果并保存相应的信息
     *$data患者的相关信息以及对应的九种体制信息
     * $tz患者对应的体制类型
     *session(userXXInf)患者的选项信息
     * session(res1)患者的九种体制信息（$data截取获得）
     * session(tzbsInf)患者信息，九种体制信息，患者对应的体制信息（$data与$tz合并获得）
     * session(flag)=1已提交状态；=0未提交状态
     */
    public function tizhiSub()
    {
        $blh = isset($_SESSION['id']) ? $_SESSION['id'] : "";
        $xh = isset($_SESSION['xh']) ? $_SESSION['xh'] : "";
        $where['xh'] = $xh;
        $where['br_id'] = $blh;
        $userInf = D('station_p')->getUserInf($where);
        //根据序号查出患者信息
        //        $userInf=$user->where('xh='.$xh.' and br_id='.$blh)->find();
        //体质辨识算法开始
        //气虚质
        $qxz = $_POST['xx2'] + $_POST['xx3'] + $_POST['xx4'] + $_POST['xx14'];
        //阳虚质
        $yangxz = $_POST['xx11'] + $_POST['xx12'] + $_POST['xx13'] + $_POST['xx29'];
        //阴虚质
        $yinxz = $_POST['xx10'] + $_POST['xx21'] + $_POST['xx26'] + $_POST['xx31'];
        //痰湿质
        $tsz = $_POST['xx9'] + $_POST['xx16'] + $_POST['xx28'] + $_POST['xx32'];
        //湿热质
        $srz = $_POST['xx23'] + $_POST['xx25'] + $_POST['xx27'] + $_POST['xx30'];
        //血瘀质
        $xyz = $_POST['xx19'] + $_POST['xx22'] + $_POST['xx24'] + $_POST['xx33'];
        //气郁质
        $qyz = $_POST['xx5'] + $_POST['xx6'] + $_POST['xx7'] + $_POST['xx8'];
        //特禀质
        $tbz = $_POST['xx15'] + $_POST['xx17'] + $_POST['xx18'] + $_POST['xx20'];
        //保存结果
        $data = array();
        $data['id'] = $xh;
        $data['bianhao'] = $blh;
        $data['br_name'] = $userInf['br_name'];
        $data['xb'] = $userInf['xb'];
        //        $data['birth'] = $userInf['cs_date'];
        $data['nl'] = $userInf['nl'];
        $data['pass'] = $userInf['pass'];
        $data['tel'] = $userInf['tel'];
        $data['dw'] = $userInf['dw'];
        $data['jz_date'] = substr($userInf['jz_date'], 0, 10);
        //        $data['date'] = substr($blh,0,4)."-".substr($blh,4,2)."-".substr($blh,6,2);
        //气虚质
        if ($qxz <= 8) {
            $data['tzname'] = '气虚质';
            $data['tzfs'] = $qxz;
            $data['tzjg'] = '否';
        }
        if ($qxz >= 9 && $qxz <= 10) {
            $data['tzname'] = '气虚质';
            $data['tzfs'] = $qxz;
            $data['tzjg'] = '倾向是';
        }
        if ($qxz >= 11) {
            $data['tzname'] = '气虚质';
            $data['tzfs'] = $qxz;
            $data['tzjg'] = '是';
        }
        //阳虚质
        if ($yangxz <= 8) {
            $data['tzname1'] = '阳虚质';
            $data['tzfs1'] = $yangxz;
            $data['tzjg1'] = '否';
        }
        if ($yangxz >= 9 && $yangxz <= 10) {
            $data['tzname1'] = '阳虚质';
            $data['tzfs1'] = $yangxz;
            $data['tzjg1'] = '倾向是';
        }
        if ($yangxz >= 11) {
            $data['tzname1'] = '阳虚质';
            $data['tzfs1'] = $yangxz;
            $data['tzjg1'] = '是';
        }
        //阴虚质
        if ($yinxz <= 8) {
            $data['tzname2'] = '阴虚质';
            $data['tzfs2'] = $yinxz;
            $data['tzjg2'] = '否';
        }
        if ($yinxz >= 9 && $yinxz <= 10) {
            $data['tzname2'] = '阴虚质';
            $data['tzfs2'] = $yinxz;
            $data['tzjg2'] = '倾向是';
        }
        if ($yinxz >= 11) {
            $data['tzname2'] = '阴虚质';
            $data['tzfs2'] = $yinxz;
            $data['tzjg2'] = '是';
        }
        //痰湿质
        if ($tsz <= 8) {
            $data['tzname3'] = '痰湿质';
            $data['tzfs3'] = $tsz;
            $data['tzjg3'] = '否';
        }
        if ($tsz >= 9 && $tsz <= 10) {
            $data['tzname3'] = '痰湿质';
            $data['tzfs3'] = $tsz;
            $data['tzjg3'] = '倾向是';
        }
        if ($tsz >= 11) {
            $data['tzname3'] = '痰湿质';
            $data['tzfs3'] = $tsz;
            $data['tzjg3'] = '是';
        }
        //湿热质
        if ($srz <= 8) {
            $data['tzname4'] = '湿热质';
            $data['tzfs4'] = $srz;
            $data['tzjg4'] = '否';
        }
        if ($srz >= 9 && $srz <= 10) {
            $data['tzname4'] = '湿热质';
            $data['tzfs4'] = $srz;
            $data['tzjg4'] = '倾向是';
        }
        if ($srz >= 11) {
            $data['tzname4'] = '湿热质';
            $data['tzfs4'] = $srz;
            $data['tzjg4'] = '是';
        }
        //血瘀质
        if ($xyz <= 8) {
            $data['tzname5'] = '血瘀质';
            $data['tzfs5'] = $xyz;
            $data['tzjg5'] = '否';
        }
        if ($xyz >= 9 && $xyz <= 10) {
            $data['tzname5'] = '血瘀质';
            $data['tzfs5'] = $xyz;
            $data['tzjg5'] = '倾向是';
        }
        if ($xyz >= 11) {
            $data['tzname5'] = '血瘀质';
            $data['tzfs5'] = $xyz;
            $data['tzjg5'] = '是';
        }
        //气郁质
        if ($qyz <= 8) {
            $data['tzname6'] = '气郁质';
            $data['tzfs6'] = $qyz;
            $data['tzjg6'] = '否';
        }
        if ($qyz >= 9 && $qyz <= 10) {
            $data['tzname6'] = '气郁质';
            $data['tzfs6'] = $qyz;
            $data['tzjg6'] = '倾向是';
        }
        if ($qyz >= 11) {
            $data['tzname6'] = '气郁质';
            $data['tzfs6'] = $qyz;
            $data['tzjg6'] = '是';
        }
        //特禀质
        if ($tbz <= 8) {
            $data['tzname7'] = '特禀质';
            $data['tzfs7'] = $tbz;
            $data['tzjg7'] = '否';
        }
        if ($tbz >= 9 && $tbz <= 10) {
            $data['tzname7'] = '特禀质';
            $data['tzfs7'] = $tbz;
            $data['tzjg7'] = '倾向是';
        }
        if ($tbz >= 11) {
            $data['tzname7'] = '特禀质';
            $data['tzfs7'] = $tbz;
            $data['tzjg7'] = '是';
        }
        //反向计分
        //平和质
        if ($_POST['xx2'] == 1) {
            $_POST['xx2'] = 5;
        } else {
            if ($_POST['xx2'] == 2) {
                $_POST['xx2'] = 4;
            } else {
                if ($_POST['xx2'] == 4) {
                    $_POST['xx2'] = 2;
                } else {
                    if ($_POST['xx2'] == 5) {
                        $_POST['xx2'] = 1;
                    }
                }
            }
        }
        if ($_POST['xx4'] == 1) {
            $_POST['xx4'] = 5;
        } else {
            if ($_POST['xx4'] == 2) {
                $_POST['xx4'] = 4;
            } else {
                if ($_POST['xx4'] == 4) {
                    $_POST['xx4'] = 2;
                } else {
                    if ($_POST['xx4'] == 5) {
                        $_POST['xx4'] = 1;
                    }
                }
            }
        }
        if ($_POST['xx5'] == 1) {
            $_POST['xx5'] = 5;
        } else {
            if ($_POST['xx5'] == 2) {
                $_POST['xx5'] = 4;
            } else {
                if ($_POST['xx5'] == 4) {
                    $_POST['xx5'] = 2;
                } else {
                    if ($_POST['xx5'] == 5) {
                        $_POST['xx5'] = 1;
                    }
                }
            }
        }
        if ($_POST['xx13'] == 1) {
            $_POST['xx13'] = 5;
        } else {
            if ($_POST['xx13'] == 2) {
                $_POST['xx13'] = 4;
            } else {
                if ($_POST['xx13'] == 4) {
                    $_POST['xx13'] = 2;
                } else {
                    if ($_POST['xx13'] == 5) {
                        $_POST['xx13'] = 1;
                    }
                }
            }
        }
        $phz = $_POST['xx1'] + $_POST['xx2'] + $_POST['xx4'] + $_POST['xx5'] + $_POST['xx13'];
        if ($phz >= 17 && $qxz <= 10 && $yangxz <= 10 && $yinxz <= 10 && $tsz <= 10 && $srz <= 10 && $xyz <= 10 && $qyz <= 10 && $tbz <= 10) {
            if ($phz >= 17 && $qxz <= 8 && $yangxz <= 8 && $yinxz <= 8 && $tsz <= 8 && $srz <= 8 && $xyz <= 8 && $qyz <= 8 && $tbz <= 8) {
                $data['tzname8'] = '平和质';
                $data['tzfs8'] = $phz;
                $data['tzjg8'] = '是';
            } else {
                $data['tzname8'] = '平和质';
                $data['tzfs8'] = $phz;
                $data['tzjg8'] = '基本是';
            }
        } else {
            $data['tzname8'] = '平和质';
            $data['tzfs8'] = $phz;
            $data['tzjg8'] = '否';
        }
        $_POST['userids'] = session('wh_userId');
        //就诊医生ID
        //体质辨识算法结束
        session('userXXInf', $_POST);
        //保存患者选项信息
        //$data患者的相关信息以及对应的九种体制信息
        $res1 = array_slice($data, 9, 27);
        $res1['userids'] = session('wh_userId');
        //就诊医生ID
        session('res1', $res1);
        //保存患者的答题结果（九种体质类型信息）
        //        体质辨识结果生成部分
        $tz = array();
        if ($data[tzjg] != '否') {
            $tz[] = $data[tzname] . "-" . $data[tzjg];
        }
        if ($data[tzjg1] != '否') {
            $tz[] = $data[tzname1] . "-" . $data[tzjg1];
        }
        if ($data[tzjg2] != '否') {
            $tz[] = $data[tzname2] . "-" . $data[tzjg2];
        }
        if ($data[tzjg3] != '否') {
            $tz[] = $data[tzname3] . "-" . $data[tzjg3];
        }
        if ($data[tzjg4] != '否') {
            $tz[] = $data[tzname4] . "-" . $data[tzjg4];
        }
        if ($data[tzjg5] != '否') {
            $tz[] = $data[tzname5] . "-" . $data[tzjg5];
        }
        if ($data[tzjg6] != '否') {
            $tz[] = $data[tzname6] . "-" . $data[tzjg6];
        }
        if ($data[tzjg7] != '否') {
            $tz[] = $data[tzname7] . "-" . $data[tzjg7];
        }
        if ($data[tzjg8] != '否') {
            $tz[] = $data[tzname8] . "-" . $data[tzjg8];
        }
        //      $tz结果内容（患者对应的体制类型）
        $this->assign('baoj', $tz);
        $tzbs = array_merge_recursive($data, $tz);
        //        dump($tzbs);die;
        session('tzbsInf', $tzbs);
        session('flag', 1);
        $this->redirect('Index/tizhi', $tzbs);
    }
    //    体质辨识提交ajax
    public function tizhiCheckedAjax()
    {
        $blh = session('id');
        $xh = session('xh');
        //      判断能保存数据
        $flag = I('post.flag');
        session('flag', $flag);
        if ($blh && $xh) {
            //          可以继续答题
            $a = 11;
            $this->ajaxReturn($a, 'json');
        } else {
            //          必须登记后才能进行答题
            $b = 22;
            $this->ajaxReturn($b, 'json');
        }
    }
    /**
     * 体质辨识结果存到数据库中
     * 如果没有该患者的记录，直接插入数据
     * 如果存在该患者的记录，询问是否要覆盖
     */
    public function tizhiSave()
    {
        session('tzbsInfo', session(tzbsInf));
        $userXXInf = session(userXXInf);
        //患者选项信息包含就诊医生id
        $res1 = session(res1);
        //患者体制信息
        $data = array();
        $data['id'] = session(xh);
        $data['bianhao'] = session(id);
        //平和质答题信息反向转换
        if ($userXXInf['xx2'] == 1) {
            $userXXInf['xx2'] = 5;
        } else {
            if ($userXXInf['xx2'] == 2) {
                $userXXInf['xx2'] = 4;
            } else {
                if ($userXXInf['xx2'] == 4) {
                    $userXXInf['xx2'] = 2;
                } else {
                    if ($userXXInf['xx2'] == 5) {
                        $userXXInf['xx2'] = 1;
                    }
                }
            }
        }
        if ($userXXInf['xx4'] == 1) {
            $userXXInf['xx4'] = 5;
        } else {
            if ($userXXInf['xx4'] == 2) {
                $userXXInf['xx4'] = 4;
            } else {
                if ($userXXInf['xx4'] == 4) {
                    $userXXInf['xx4'] = 2;
                } else {
                    if ($userXXInf['xx4'] == 5) {
                        $userXXInf['xx4'] = 1;
                    }
                }
            }
        }
        if ($userXXInf['xx5'] == 1) {
            $userXXInf['xx5'] = 5;
        } else {
            if ($userXXInf['xx5'] == 2) {
                $userXXInf['xx5'] = 4;
            } else {
                if ($userXXInf['xx5'] == 4) {
                    $userXXInf['xx5'] = 2;
                } else {
                    if ($userXXInf['xx5'] == 5) {
                        $userXXInf['xx5'] = 1;
                    }
                }
            }
        }
        if ($userXXInf['xx13'] == 1) {
            $userXXInf['xx13'] = 5;
        } else {
            if ($userXXInf['xx13'] == 2) {
                $userXXInf['xx13'] = 4;
            } else {
                if ($userXXInf['xx13'] == 4) {
                    $userXXInf['xx13'] = 2;
                } else {
                    if ($userXXInf['xx13'] == 5) {
                        $userXXInf['xx13'] = 1;
                    }
                }
            }
        }
        //平和质答题信息反向转换完毕
        //      数组组合 $data患者病历号和序号 $res1患者的
        $userInf = array_merge($data, $userXXInf);
        $res = array_merge($data, $res1);
        $jbxx = D('tz_jbxx');
        $jieguo = D('tz_jieguo');
        //查询条件
        $where['id'] = $data['id'];
        $where['bianhao'] = $data['bianhao'];
        //根据序号和病历号查询病人的相关记录
        $userInf1 = $jbxx->getUserAnsInf($where);
        //存放患者选项信息的表
        $jieguoInf1 = $jieguo->getUserAnsJG($where);
        //存放答题结果信息表
        //        $userInf1=$jbxx->where("id=".$data['id']." and bianhao=".$data['bianhao'])->select();
        //        $jieguoInf1=$jieguo->where("id=".$data['id']." and bianhao=".$data['bianhao'])->select();
        //如果存在相关记录禁止插入，只能修改或X
        if ($userInf1 && $jieguoInf1) {
            session('save', 1);
            $upUserInf = $jbxx->where($where)->save($userInf);
            $upJG = $jieguo->where($where)->save($res);
            if ($upUserInf || $upJG) {
                $this->success('更新成功！', U('Index/tizhi'), 1);
            } else {
                $this->error('更新失败！', U('Index/tizhi'), 1);
            }
        } else {
            session('save', 1);
            //          如果不存在相关记录则可以添加
            $addUserInf = $jbxx->add($userInf);
            $addJG = $jieguo->add($res);
            if ($addUserInf && $addJG) {
                $this->success('保存成功！', U('Index/tizhi'), 1);
            } else {
                $this->error('保存失败！', U('Index/tizhi'), 1);
            }
        }
    }
    public function saveAsTizhi()
    {
        $blh = session('id');
        $xh = session('xh');
        $userWhere['id'] = $xh;
        $userWhere['bianhao'] = $blh;
        $userInf = D('tz_jbxx')->getUserAns($userWhere);
        //患者的答题记录表,患者的选项信息
        $data = D('tz_jieguo')->getUserInfAndAnsJG($userWhere);
        //患者的个人信息和答题结果
        $tzbs = session(tzbsInf);
        if ($tzbs) {
            $res1 = array_slice($tzbs, 0, 36);
            $res2 = array_slice($tzbs, 36);
            $this->assign('res1', $res1);
            $this->assign('baoj', $res2);
            $this->display();
        } else {
            //有历史纪录但是没有提交的则显示历史纪录
            //日期去掉时分秒
            $data['jz_date'] = substr($data['jz_date'], 0, 10);
            $this->assign('res1', $data);
            //患者的答题结果
            $this->assign('userCheckedInf', $userInf);
            //患者的个人信息和答案信息
            //体质辨识结果生成部分
            $tz = array();
            if ($data[tzjg] != '否') {
                $tz[] = $data[tzname] . "-" . $data[tzjg];
            }
            if ($data[tzjg1] != '否') {
                $tz[] = $data[tzname1] . "-" . $data[tzjg1];
            }
            if ($data[tzjg2] != '否') {
                $tz[] = $data[tzname2] . "-" . $data[tzjg2];
            }
            if ($data[tzjg3] != '否') {
                $tz[] = $data[tzname3] . "-" . $data[tzjg3];
            }
            if ($data[tzjg4] != '否') {
                $tz[] = $data[tzname4] . "-" . $data[tzjg4];
            }
            if ($data[tzjg5] != '否') {
                $tz[] = $data[tzname5] . "-" . $data[tzjg5];
            }
            if ($data[tzjg6] != '否') {
                $tz[] = $data[tzname6] . "-" . $data[tzjg6];
            }
            if ($data[tzjg7] != '否') {
                $tz[] = $data[tzname7] . "-" . $data[tzjg7];
            }
            if ($data[tzjg8] != '否') {
                $tz[] = $data[tzname8] . "-" . $data[tzjg8];
            }
            //$tz结果内容
            $this->assign('baoj', $tz);
            $this->display();
        }
    }
    //    中医调养
    public function tiaoyang()
    {
        $condition['br_id'] = session("id");
        $condition['xh'] = session("xh");
        $userAge = M('station_p')->field("nl")->where($condition)->find();
        $this->assign("age", $userAge['nl']);
        $this->display();
    }
    public function saveAsTy1()
    {
        $this->display();
    }
    public function saveAsTy2()
    {
        $this->display();
    }
    public function saveAsTy3()
    {
        $this->display();
    }
}