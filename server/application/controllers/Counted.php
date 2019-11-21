<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Counted extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 测算审核
			$begin = Xcon::date();
			$end = Xcon::date();
            $result = Xcon::getsBy('xvData', "count=1 and counted=0 and create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 测算审核查询
            $params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
            $end = Xcon::array_key($params, 'end');

            $result = Xcon::getsBy('xvData', "count=1 and counted=0 and create_time between '$begin' and '$end'");

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

    public function back()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 测算标的退回
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            // 查询测算标的
            $data = Xcon::checkByUid('xvData', $uid);
            $data_id = $data->id;
            $exam_id = Xcon::EXAM_COUNT;

            $result = Xcon::delBy('xcDataExam', compact('data_id', 'exam_id'));

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
            $exam_time = date('Y-m-d H:i:s');

            // 检测标的是否通过审核
            Xcon::existBy('xcDataExam', compact('data_id', 'exam_id'), '“标的”已经通过测算审核！');

            // 提交
            $uid = Xcon::uid();
            $result = Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'user_id', 'exam_time'));

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
