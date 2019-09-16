<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            $result = Xcon::gets('xvUser');
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
            $part_id = Xcon::array_key($params, 'part_id');
            $group_id = Xcon::array_key($params, 'group_id');

            // 检测帐号是否存
            Xcon::checkBy('xcUser', compact('id', 'uid'), '帐号不存在！');

            // 加密
            $validate = md5($id . Xcon::TO_KEN . $group_id);

            // 修改
            Xcon::setByUid('xcUser', compact('name', 'part_id', 'group_id', 'validate'), $uid);

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
