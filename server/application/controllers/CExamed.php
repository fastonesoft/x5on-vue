<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C2Exam extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
			// 获取用户信息
			$user_id = $userinfor->id;

			// 测算审核
			$begin = Xcon::date();
			$end = Xcon::date();
            $result = Xcon::getsBy('xvData', "counted=1 and teamed=0 and counted_user_id='$user_id' and create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
			// 获取用户信息
			$user_id = $userinfor->id;

            // 测算审核查询
            $params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
            $end = Xcon::array_key($params, 'end');
            $teamed = Xcon::array_key($params, 'teamed');
            $result = Xcon::getsBy('xvData', "counted=1 and teamed=$teamed and counted_user_id='$user_id' and create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function tax()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 测算列表
            $params = Xcon::params();
            $data_id = Xcon::array_key($params, 'data_id');

            $result = Xcon::getsBy('xvDataTax', compact('data_id'));
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
