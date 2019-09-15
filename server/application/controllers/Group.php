<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                $result = Xcon::gets('xvGroup');

                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function add()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                $params = Xcon::params();
                $params['uid'] = Xcon::uid();

                // 重复编号检测
                Xcon::existById('xcGroup', Xcon::array_key($params, 'id'));
                // 重复名称检测
                $name = Xcon::array_key($params, 'name');
                Xcon::existBy('xcGroup', compact('name'), '“名称”重复');

                Xcon::add('xcGroup', $params);
                $result = Xcon::getByUid('xvGroup', $params['uid']);

                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function edit()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                $params = Xcon::params();
                $uid = Xcon::array_key($params, 'uid');

                Xcon::setByUid('xcGroup', $params, $uid);
                $result = Xcon::getByUid('xvGroup', $uid);

                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function del()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                $params = Xcon::params();

                // 删除之前要确认一下
                $uid = Xcon::array_key($params, 'uid');
                $group = Xcon::checkByUid('xcGroup', $uid);
                $group_id = $group->id;

                // 检测路由、权限、用户列表
                Xcon::existBy('xcGroupMenu', compact('group_id'), '编号已存在路由列表');
                Xcon::existBy('xcGroupAction', compact('group_id'), '编号已存在权限列表');                // 检测用户列表
                Xcon::existBy('xcUser', compact('group_id'), '编号已存在用户列表');

                // 删除
                $result = Xcon::delByUid('xcGroup', $uid);

                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

}
