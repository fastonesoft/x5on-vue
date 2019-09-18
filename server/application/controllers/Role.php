<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            $result = Xcon::getsBy('xvGroup', null, 'id');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function menus()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 分组菜单数据
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            $group = Xcon::checkByUid('xcGroup', $uid);
            $group_id = $group->id;

            // 菜单类型
            $types = Xcon::getsBy('xvMenuType', null, 'type_id');
            // 用户级所有菜单列表
            $menus = Xcon::getsBy('xvMenu', null, 'id');
            // 分组菜单列表
            $group_menus = Xcon::getsBy('xcGroupMenu', compact('group_id'));

            $result = compact('types', 'menus', 'group_menus');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function upto()
    {
        Xcon::loginCheck(function ($userinfor) {
            $params = Xcon::params();

            $group_uid = Xcon::array_key($params, 'uid');
            $uids_string = Xcon::array_key($params, 'uids');
            $uids = json_decode($uids_string);

            // 获取分组编号
            $group = Xcon::checkByUid('xcGroup', $group_uid);
            $group_id = $group->id;

            $result = 0;
            foreach ($uids as $uid => $checked) {
                $menu = Xcon::checkByUid('xcMenu', $uid);
                $menu_id = $menu->id;

                $group_menu = Xcon::getBy('xcGroupMenu', compact('group_id', 'menu_id'));
                if ($checked) {
                    if ($group_menu === null) {
                        // 菜单加权，添加
                        $uid = Xcon::uid();
                        $result = Xcon::add('xcGroupMenu', compact('uid', 'group_id', 'menu_id'));
                    }
                } else {
                    if ($group_menu !== null) {
                        // 菜单减权，删除
                        $uid = $group_menu->uid;
                        $result = Xcon::delByUid('xcGroupMenu', $uid);
                    }
                }
            }
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }


}
