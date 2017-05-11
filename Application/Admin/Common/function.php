<?php
/**
 * Created by PhpStorm.
 * User: Miracle7Kill
 * Date: 2017/3/6
 * Time: 10:16
 */
/**
 * 后台左侧菜单
 * @param array $arr
 * @param int $parentId
 * @return array
 */
function menuToTree(array $arr, $parentId = 0)
{
    $resData = array();

    foreach ($arr as $key => $item) {
        if ($item['parent'] == $parentId) {
            unset($arr[$key]);
            $resData[$key] = array_merge($item, array('sub' => menuToTree($arr, $key)));
        }
    }
    return $resData;
}

/**
 * 右侧头部菜单
 * @param array $arr
 * @param $key
 * @return array
 */
function activeTree(array $arr, $key)
{
    $resData = array();

    while($key) {
        if (isset($arr[$key])) {
            $resData[$key] = $arr[$key];
            $key = $resData[$key]['parent'];
        } else {
            $key = false;
        }
    }
    ksort($resData);
    return $resData;
}

function getHospNameByPid($pid)
{
    $res = D('About')->field('hospital')->where("id = '$pid'")->find();
    return $res['hospital'];
}

function getRuleName($uid)
{
    $model = M('members');
    $res = $model->alias('m')
        ->join("left join auth_group_access as aga on m.id = aga.uid")
        ->join("left join auth_group as ag on aga.group_id = ag.id")
        ->field('title')->where(array('m.id' => $uid))->find();
    return $res['title'];
}