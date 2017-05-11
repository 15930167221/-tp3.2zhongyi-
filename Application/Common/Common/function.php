<?php
/**
 * TODO 基础分页的相同代码封装，使前台的代码更少
 * @param $count 要分页的总记录数
 * @param int $pagesize 每页查询条数
 * @return \Think\Page
 */
function getpage($count, $pagesize = 10) {
  $p = new Think\Page($count, $pagesize);
  $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录 第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
  $p->setConfig('prev', '上一页');
  $p->setConfig('next', '下一页');
  $p->setConfig('last', '末页');
  $p->setConfig('first', '首页');
  $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
  $p->lastSuffix = false;//最后一页不显示为总页数
  return $p;
}
/**
 * 调用存储过程生成删除文件
 * @access public
 * @param mixed $id 治疗指南code
 * @return html
 */
function myProcedure($id)
{
    $mod = M();
    $nid = str_replace('/','_',$id);
    $url = 'g:\php\\'.$nid.'.html';
//    $sql = "{call ReadFile('$id', '$url')}";
    $sql = "SET NOCOUNT ON
        DECLARE	@return_value int
        EXEC	@return_value = [dbo].[ReadFile]
		    @code = N'$id',
		    @filename = N'$url'
        SELECT	'Return Value' = @return_value";
    $mod->query($sql);

    $word = file_get_contents("http://192.168.1.249:1234/$nid.html");
    //$sqlkill = "{call DeleteFile('$url')}";

    $sqlkill = "SET NOCOUNT ON 
        DECLARE	@return_value int
        EXEC	@return_value = [dbo].[DeleteFile]
                @filename = N'$url'
        SELECT	'Return Value' = @return_value";
    $mod->query($sqlkill);
    return $word;
}

function getZhenDuanContent($id, $uid)
{
    $mod = M();
    $name = $id.'_'.$uid;
    $url = 'g:\php\\'.$name.'.html';
    $sql = "SET NOCOUNT ON
        DECLARE @return_value int
        EXEC  @return_value = [dbo].[ReadFile_LCZD]
    @UnitID = N'$uid',
    @id = N'$id',
    @filename = N'$url'
        SELECT  'Return Value' = @return_value";
    $mod->query($sql);
    $word = file_get_contents("http://192.168.1.249:1234/$name.html");
    // echo $word;die();
    // $sqlkill = "SET NOCOUNT ON 
    //     DECLARE @return_value int
    //     EXEC  @return_value = [dbo].[DeleteFile]
    //             @filename = N'$url'
    //     SELECT  'Return Value' = @return_value";
    // $mod->query($sqlkill);

    return $word;
}


function myfunction($a,$b)
{
  if ((substr($a,0,9))===(substr($b,0,9)))
  {
    return 0;
  }
  return ($a>$b)?1:-1;
}

function getNameByCode($code)
{
  $res = D('user_info_dict')->where(array('id' => $code))->field('userName')->find();
  return $res['username'];
}

function getOperatorNameByCode($code)
{
  $res = D('p_price_list')->where(array('ITEM_CODE'=>$code))->field('item_name')->find();
  // var_dump($res);die;
  return $res['item_name'];
}
//后台西药字典剂型
function getjixingNameByCode($code)
{
  $res = D('sys_dm_jx')->where(array('jxdm'=>$code))->field('jxmc')->find();
  // var_dump($res);die;
  return $res['jxmc'];
}
//后台西药字典含量单位
function gethangliangNameByCode($code)
{
  $res = D('sys_dm_jldw')->where(array('dwdm'=>$code))->field('dw')->find();
  // var_dump($res);die;
  return $res['dw'];
}

function i_array_column($input, $columnKey, $indexKey=null){
    if(!function_exists('array_column')){
        $columnKeyIsNumber  = (is_numeric($columnKey))?true:false;
        $indexKeyIsNull            = (is_null($indexKey))?true :false;
        $indexKeyIsNumber     = (is_numeric($indexKey))?true:false;
        $result                         = array();
        foreach((array)$input as $key=>$row){
            if($columnKeyIsNumber){
                $tmp= array_slice($row, $columnKey, 1);
                $tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null;
            }else{
                $tmp= isset($row[$columnKey])?$row[$columnKey]:null;
            }
            if(!$indexKeyIsNull){
                if($indexKeyIsNumber){
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && !empty($key))?current($key):null;
                    $key = is_null($key)?0:$key;
                }else{
                    $key = isset($row[$indexKey])?$row[$indexKey]:0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    }else{
        return array_column($input, $columnKey, $indexKey);
    }
}