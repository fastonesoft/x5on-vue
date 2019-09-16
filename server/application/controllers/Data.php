<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 标的清单
            $result = Xcon::gets('xvDataNotConfirm');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function add()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 标的清单添加
            $params = Xcon::params();

            // 增加 uid
            $uid = Xcon::uid();
            $params['uid'] = $uid;
            $params['create_time'] = date('Y-m-d');

            // 重复编号检测
            $id = $params['id'];
            Xcon::existById('xcData', $id);

            // 添加数据
            Xcon::add('xcData', $params);

            // 查询出添加数据
            $result = Xcon::getByUid('xvDataNotConfirm', $uid);

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function edit()
    {
        echo '  --------edit ---------';
    }

    public function del()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 提交测算
            $params = Xcon::params();
            $uid_string = Xcon::array_key($params, 'uids');

            $uids = explode(',', $uid_string);
            foreach ($uids as $uid) {
                Xcon::delByUid('xcData', $uid);
            }

            $result = Xcon::gets('xvDataNotConfirm');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 标的查询
            $params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
            $end = Xcon::array_key($params, 'end');

            $result = Xcon::getsBy('xvDataNotConfirm', "create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function upto()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 提交测算
            $params = Xcon::params();
            $uid_string = Xcon::array_key($params, 'uids');

            $uids = explode(',', $uid_string);
            foreach ($uids as $uid) {
                $confirm_time = date('Y-m-d');
                $confirm_user_id = $userinfor->id;
                Xcon::setByUid('xcData', compact('confirm_time', 'confirm_user_id'), $uid);
            }

            $result = Xcon::gets('xvDataNotConfirm');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
