<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backed extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 反馈清单
            $result = Xcon::getsBy('xvData', 'back=1 and backed=0');

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 反馈查询
            $params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
            $end = Xcon::array_key($params, 'end');

            $result = Xcon::getsBy('xvData', "back=1 and backed=0 and create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function back()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 反馈退回
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            // 查询反馈
            $data = Xcon::checkByUid('xvData', $uid);
            $data_id = $data->id;
            $exam_id = Xcon::EXAM_BACK;

            $result = Xcon::delBy('xcDataExam', compact('data_id', 'exam_id'));

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function exam()
    {
        Xcon::loginCheck(function ($userinfor) {
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            // 检测反馈是否存在
            $data = Xcon::checkByUid('xcData', $uid);
            $data_id = $data->id;

            // 审核，结束
            $user_id = $userinfor->id;
            $exam_id = Xcon::EXAM_BACKED;
            $exam_time = date('Y-m-d H:i:s');

            // 检测反馈是否通过审核
            Xcon::existBy('xcDataExam', compact('data_id', 'exam_id'), '“反馈”标的已经通过审核！');

            // 提交
            $uid = Xcon::uid();
            $result = Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'user_id', 'exam_time'));

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
