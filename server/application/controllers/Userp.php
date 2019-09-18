<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userp extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 查询用户信息
            $id = $userinfor->id;
            $group_id = $userinfor->group_id;
            // 查询部门
            $user = Xcon::checkById('xvUser', $id);
            // 返回用户列表
            $part_id = $user->part_id;
            $result = Xcon::getsBy('xvUser', "part_id=$part_id and group_id<$group_id", 'group_id, id');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function group()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 查询用户信息
            $id = $userinfor->id;
            $group_id = $userinfor->group_id;
            // 查询部门
            $user = Xcon::checkById('xvUser', $id);
            $part_id = $user->part_id;

            $result = Xcon::getsBy('xvGroup', "part_id=$part_id and id<$group_id", 'id');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function add()
    {
        Xcon::loginCheck(function ($userinfor) {
            $params = Xcon::params();
            $params['uid'] = Xcon::uid();

            // 帐号、名称检测
            $id = Xcon::array_key($params, 'id');
            $name = Xcon::array_key($params, 'name');
            $pass = Xcon::array_key($params, 'pass');
            $group_id = Xcon::array_key($params, 'group_id');

            Xcon::existById('xcUser', $id);
            Xcon::existBy('xcUser', compact('name'), '“帐号名称”已存在！');

            // 加密
            $params['pass'] = md5($pass);
            $params['validate'] = md5($id . Xcon::TO_KEN . $group_id);

            // 添加
            Xcon::add('xcUser', $params);
            $result = Xcon::getById('xvUser', $id);

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function edit()
    {
        Xcon::loginCheck(function ($userinfor) {
            $params = Xcon::params();

            // 帐号、名称检测
            $id = Xcon::array_key($params, 'id');
            $uid = Xcon::array_key($params, 'uid');
            $name = Xcon::array_key($params, 'name');
            $group_id = Xcon::array_key($params, 'group_id');

            // 检测帐号是否存
            Xcon::checkBy('xcUser', compact('id', 'uid'), '帐号不存在！');

            // 加密
            $validate = md5($id . Xcon::TO_KEN . $group_id);

            // 修改
            Xcon::setByUid('xcUser', compact('name', 'group_id', 'validate'), $uid);

            $result = Xcon::getByUid('xvUser', $uid);
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }
    
    public function pass()
    {
        Xcon::loginCheck(function ($userinfor) {
            $params = Xcon::params();

            // 帐号、名称检测
            $id = Xcon::array_key($params, 'id');
            $uid = Xcon::array_key($params, 'uid');
            $pass = Xcon::array_key($params, 'pass');

            // 检测帐号是否存
            Xcon::checkBy('xcUser', compact('id', 'uid'), '帐号不存在！');

            // 加密
            $pass = md5($pass);

            // 修改
            $result = Xcon::setByUid('xcUser', compact('pass'), $uid);
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function del()
    {
        Xcon::loginCheck(function ($userinfor) {
            $params = Xcon::params();

            $uid = Xcon::array_key($params, 'uid');
            Xcon::checkByUid('xcUser', $uid);

            $result = Xcon::delByUid('xcUser', $uid);

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function find()
    {
        echo '  --------find -------------';
    }

}
