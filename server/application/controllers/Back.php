<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Back extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 测算反馈
                $result = Xcon::gets('xvDataNotDone');
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
                // 提交测算
                $uid_string = Xcon::param('uids');

                $uids = explode(',', $uid_string);
                foreach ($uids as $uid) {
                    Xcon::delByUid('xcData', $uid);
                }

                $result = Xcon::gets('xvDataNotDone');
                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 标的查询
                $begin = Xcon::param('begin');
                $end = Xcon::param('end');

                $result = Xcon::getsBy('xvDataNotDone', "create_time between '$begin' and '$end'");

                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function upto()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 测算结束
                $uid = Xcon::param('uid');

                $done_time = date('Y-m-d');
                $done_user_id = $userinfor->id;
                Xcon::setByUid('xcData', compact('done_time', 'done_user_id'), $uid);

                $result = Xcon::gets('xvDataNotDone');
                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

}
