<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Count extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 测算清单
            $datas = Xcon::getsBy('xvData', 'dataed=1 and count=0');
            // 统计结果
            $count = Xcon::getBy('xvDataCount', null);
            Xcon::json(Xcon::NO_ERROR, compact('datas', 'count'));
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 标的查询
            $params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
            $end = Xcon::array_key($params, 'end');

            $result = Xcon::getsBy('xvData', "dataed=1 and count=0 and create_time between '$begin' and '$end'");

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

            $result = Xcon::gets('xvDataNotGuess');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }



    public function upto()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 测算结束
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            $guess_time = date('Y-m-d');
            $guess_user_id = $userinfor->id;
            Xcon::setByUid('xcData', compact('guess_time', 'guess_user_id'), $uid);

            $result = Xcon::gets('xvDataNotGuess');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
