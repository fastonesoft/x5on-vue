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
    
    public function exam()
    {
        Xcon::loginCheck(function ($userinfor) {
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            // 检测测算审核是否存在
            $data = Xcon::checkByUid('xcData', $uid);
            $data_id = $data->id;

            // 测算，提交审核
            $user_id = $userinfor->id;
            $exam_id = Xcon::EXAM_COUNTED;
            $exam_time = Xcon::datetime();
			$examed = 1;
			$team = 1;

            // 检测标的是否指定复核人员
            $data_exam = Xcon::getBy('xcDataExam', compact('data_id', 'exam_id'));
            if ($data_exam === null) {
				// 没有指定复核人员，提单复核信息
				$uid = Xcon::uid();
				$result = Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'user_id', 'exam_time', 'examed', 'team'));
			} else {
            	// 指定复核人员，修改复核状态
				$result = Xcon::setByUid('xcDataExam', compact('exam_time', 'examed'), $data_exam->uid);
			}

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
