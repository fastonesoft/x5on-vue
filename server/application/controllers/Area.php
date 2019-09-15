<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                $result = Xcon::gets('xcArea');

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
                Xcon::existById('xcArea', Xcon::array_key($params, 'id'));
                // 重复名称检测
                $name = Xcon::array_key($params, 'name');
                Xcon::existBy('xcArea', compact('name'), '“名称”重复');

                Xcon::add('xcArea', $params);
                $result = Xcon::getByUid('xcArea', $params['uid']);

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

                Xcon::setByUid('xcArea', $params, $uid);
                $result = Xcon::getByUid('xcArea', $uid);

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
                $part = Xcon::checkByUid('xcArea', $uid);
                $part_id = $part->id;

                // 检测分组列表
                Xcon::existBy('xcGroup', compact('part_id'), '编号已存在分组列表');
                // 检测用户列表
                Xcon::existBy('xcUser', compact('part_id'), '编号已存在用户列表');

                // 删除
                $result = Xcon::delByUid('xcArea', $uid);

                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

}
