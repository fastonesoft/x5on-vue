<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Count extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 测算清单
                $result = Xcon::gets('xvDataNotGuess');
                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function add()
    {
        Xcon::loginCheck(function ($userinfor) {

        });
    }

    public function edit()
    {
        echo '  --------edit ---------';
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

                $result = Xcon::gets('xvDataNotGuess');
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

                $result = Xcon::getsBy('xvDataNotGuess', "create_time between '$begin' and '$end'");

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

                $guess_time = date('Y-m-d');
                $guess_user_id = $userinfor->id;
                Xcon::setByUid('xcData', compact('guess_time', 'guess_user_id'), $uid);

                $result = Xcon::gets('xvDataNotGuess');
                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

}
