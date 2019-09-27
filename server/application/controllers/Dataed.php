<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataed extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 标的清单
            $result = Xcon::getsBy('xvData', 'data=1 and dataed=0');

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

            $result = Xcon::getsBy('xvData', "data=1 and dataed=0 and create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function back()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 标的退回
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            // 查询标的
            $data = Xcon::checkByUid('xvData', $uid);
            

            $result = Xcon::getsBy('xvData', "data=1 and dataed=0 and create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function upto()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 提交测算
            $params = Xcon::params();
            $uid_string = Xcon::array_key($params, 'uids');

            $result = 0;
            $uids = explode(',', $uid_string);
            foreach ($uids as $uid) {
                $user_id = $userinfor->id;
                $exam_id = Xcon::EXAM_DATA;
                $exam_time = date('Y-m-d H:i:s');

                // 检测标的是否存在
                $data = Xcon::checkByUid('xcData', $uid);
                $data_id = $data->id;

                // 检测标的是否已经提交审核
                Xcon::existBy('xcDataExam', compact('data_id', 'exam_id'), '“标的”已经提交审核！');

                // 提交
                $uid = Xcon::uid();
                Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'user_id', 'exam_time'));

                $result++;
            }
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
