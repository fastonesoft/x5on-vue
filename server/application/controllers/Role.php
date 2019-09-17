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
            $result = Xcon::params();
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }


}
