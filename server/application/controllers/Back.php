<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Back extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 测算反馈
            $result = Xcon::gets('xvDataNotDone');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
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

            $result = Xcon::gets('xvDataNotDone');
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

            $result = Xcon::getsBy('xvDataNotDone', "create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function upto()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 测算结束
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            $done_time = date('Y-m-d');
            $done_user_id = $userinfor->id;
            Xcon::setByUid('xcData', compact('done_time', 'done_user_id'), $uid);

            $result = Xcon::gets('xvDataNotDone');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
