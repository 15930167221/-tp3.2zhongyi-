<?php
/**
 * Created by PhpStorm.
 * User: Miracle7Kill
 * Date: 2017/3/6
 * Time: 9:46
 * 后台自定义菜单
 * @return  name 菜单名称
 * @return route 路径
 * @return parent 关联
 * @return level 级别
 * @return icon 图标
 */
return [
//    '1' => ['name' => '控制面板', 'route' => 'Admin/Index/index', 'parent' => 0, 'level' => 1, 'icon' =>
//        'Hui-iconfont-home'],
    '2' => ['name' => '医疗机构管理', 'route' => '', 'parent' => 0, 'level' => 1, 'icon' =>
        'Hui-iconfont-gongsi'],
        '200' => ['name' => '医疗机构列表', 'route' => 'Admin/About/index', 'parent' => '2', 'level' => 2,
            'icon' => ''],
    '3' => ['name' => '用户管理', 'route' => '', 'parent' => 0, 'level' => 1, 'icon' => 'Hui-iconfont-user'],
        '300' => ['name' => '用户列表', 'route' => 'Admin/User/index', 'parent' => 3, 'level' => 2, 'icon' => ''],
    '4' => ['name' => '后台管理', 'route' => '', 'parent' => 0, 'level' => 1, 'icon' => 'Hui-iconfont-user-group'],
        '400' => ['name' => '管理员列表' ,'route' => 'Admin/Members/index', 'parent' => 4, 'level' => 2,
            'icon' => ''],
        '401' => ['name' => '角色管理' ,'route' => 'Admin/AuthGroup/index', 'parent' => 4, 'level' => 2, 'icon' => ''],
//        '402' => ['name' => '权限管理' ,'route' => 'Admin/AuthRole/index', 'parent' => 4, 'level' => 2, 'icon' => ''],
//    '5' =>  ['name' => '参数设置' ,'route' => '', 'parent' => 0, 'level' => 1, 'icon' => 'Hui-iconfont-canshu'],
//        '500' => ['name' => '参数设置' ,'route' => '', 'parent' => 5, 'level' => 2, 'icon' => ''],
//    '6' => ['name' => '病例列表' ,'route' => '', 'parent' => 0, 'level' => 1, 'icon' => 'Hui-iconfont-list'],
//        '600' => ['name' => '病例列表' ,'route' => '', 'parent' => 6, 'level' => 2, 'icon' => ''],
    '7' => ['name' => '数据字典维护' ,'route' => '', 'parent' => 0, 'level' => 1, 'icon' => 'Hui-iconfont-system'],

        '701' => ['name' => '剂型字典' ,'route' => 'Admin/Jixing/index', 'parent' => 7, 'level' => 2, 'icon' => ''],
        '702' => ['name' => '药品/项目单位字典' ,'route' => 'Admin/Yaopinxm/index', 'parent' => 7, 'level' => 2, 'icon' => ''],
        '703' => ['name' => '医嘱字典' ,'route' => 'Admin/Yizhu/index', 'parent' => 7, 'level' => 2, 'icon' => ''],

        '704' => ['name' => '生产厂商字典' ,'route' => '', 'parent' => 7, 'level' => 2, 'icon' => ''],
        '705' => ['name' => '收费项目字典' ,'route' => 'Admin/Shoufei/index', 'parent' => 7, 'level' => 2, 'icon' => ''],
        '706' => ['name' => '西成药字典' ,'route' => 'Admin/Xiyao/index', 'parent' => 7, 'level' => 2, 'icon' => ''],
        '707' => ['name' => '用法字典' ,'route' => 'Admin/Yongfa/index', 'parent' => 7, 'level' => 2, 'icon' => ''],
        '708' => ['name' => '中药字典' ,'route' => 'Admin/China/index', 'parent' => 7, 'level' => 2, 'icon' => ''],
        '709' => ['name' => '给药途径字典' ,'route' => 'Admin/Tujing/index', 'parent' => 7, 'level' => 2, 'icon' => ''],
        '710' => ['name' => '频率字典' ,'route' => 'Admin/Pinlv/index', 'parent' => 7, 'level' => 2, 'icon' => ''],
    '8' => ['name' => '配伍禁忌维护' ,'route' => '', 'parent' => 0, 'level' => 1, 'icon' => ''],
        '800' => ['name' => '十八反' ,'route' => 'Admin/TabooShiba/index', 'parent' => 8, 'level' => 2, 'icon' => ''],
        '801' => ['name' => '十九畏' ,'route' => 'Admin/TabooShijiu/index', 'parent' => 8, 'level' => 2, 'icon' => ''],
        '802' => ['name' => '孕妇禁忌' ,'route' => 'Admin/TabooYunfu/index', 'parent' => 8, 'level' => 2, 'icon' => ''],
        '803' => ['name' => '药量禁忌' ,'route' => 'Admin/TabooYaoliang/index', 'parent' => 8, 'level' => 2, 'icon' => ''],
    '9' => ['name' => '药品对照' ,'route' => '', 'parent' => 0, 'level' => 1, 'icon' => ''],
        '900' => ['name' => '药品对照' ,'route' => '', 'parent' => 9, 'level' => 2, 'icon' => ''],
    '10' => ['name' => '疾病信息对照' ,'route' => '', 'parent' => 0, 'level' => 1, 'icon' => ''],
        '1000' => ['name' => '疾病信息对照' ,'route' => '', 'parent' => 10, 'level' => 2, 'icon' => ''],

];