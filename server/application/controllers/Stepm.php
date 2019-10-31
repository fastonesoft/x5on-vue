<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stepm extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 协作清单（未完成）
            $result = Xcon::getsBy('xvData', 'backed=0');

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 协作清单
            $params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
            $end = Xcon::array_key($params, 'end');

            $result = Xcon::getsBy('xvData', "backed=0 and create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function user() {
        Xcon::loginCheck(function ($userinfor) {
            // 协作进度
            $params = Xcon::params();
            $data_uid = Xcon::array_key($params, 'data_uid');

            // 检测标的是否存在
            $data = Xcon::checkByUid('xvData', $data_uid);
            $data_id = $data->id;

            // 查询审核用户
            $result = Xcon::getsBy('xvDataExamUser', compact('data_id'), 'exam_id');

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function back() {
        Xcon::loginCheck(function ($userinfor) {
            // 协作干预
            $params = Xcon::params();
            $value = Xcon::array_key($params, 'value');
            $data_uid = Xcon::array_key($params, 'data_uid');

            // 检测标的是否存在
            $data = Xcon::checkByUid('xvData', $data_uid);
            $data_id = $data->id;

            // 撤销最近
            $value--;
            $exam_id = pow(2, $value);

            // 删除审核用户
            Xcon::delBy('xcDataExamUser', compact('data_id', 'exam_id'));

            $result = Xcon::getsBy('xvDataExamUser', compact('data_id'), 'exam_id');

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
