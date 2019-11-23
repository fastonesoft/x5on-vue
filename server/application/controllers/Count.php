<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Count extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 获取用户信息
			$user_id = $userinfor->id;

            // 测算清单
			$begin = Xcon::date();
			$end = Xcon::date();
            $datas = Xcon::getsBy('xvData', "alloted=1 and count=0 and (count_user_id='$user_id' or count_user_id is null) and create_time between '$begin' and '$end'");
            // 税种列表
            $taxs = Xcon::gets('xcTax');
            // 统计结果
            $count = Xcon::getBy('xvDataCount', null);
            Xcon::json(Xcon::NO_ERROR, compact('datas', 'taxs', 'count'));
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 获取用户信息
			$user_id = $userinfor->id;

            // 测算标的查询
            $params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
            $end = Xcon::array_key($params, 'end');

            $result = Xcon::getsBy('xvData', "alloted=1 and count=0 and (count_user_id='$user_id' or count_user_id is null) and create_time between '$begin' and '$end'");

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

    public function add()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 添加测算数据
            $params = Xcon::params();
            $data_id = Xcon::array_key($params, 'data_id');
            $tax_id = Xcon::array_key($params, 'tax_id');
            $tax_base = Xcon::array_key($params, 'tax_base');
            $tax_percent = Xcon::array_key($params, 'tax_percent');
            $tax_amount = Xcon::array_key($params, 'tax_amount');
            $uid = Xcon::uid();

            // 检测税种是否已添加
            Xcon::existBy('xcDataTax', compact('data_id', 'tax_id'), '当前税种已添加！');

            Xcon::add('xcDataTax', compact('uid', 'data_id', 'tax_id', 'tax_base', 'tax_percent', 'tax_amount'));

            $result = Xcon::getByUid('xvDataTax', $uid);
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function del()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 删除测算税种
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            $result = Xcon::delByUid('xcDataTax', $uid);

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function upto()
    {
        Xcon::loginCheck(function ($userinfor) {
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            // 检测标的是否存在
            $data = Xcon::checkByUid('xcData', $uid);
            $data_id = $data->id;

            // 测算，提交审核
            $user_id = $userinfor->id;
            $exam_id = Xcon::EXAM_COUNT;
            $exam_time = Xcon::datetime();
            $examed = 1;
			$team = 1;

            // 检测标的是否指定测算人员
            $data_exam = Xcon::getBy('xcDataExam', compact('data_id', 'exam_id'));
            if ($data_exam === null) {
				// 没有指定人员，提交测算信息
				$uid = Xcon::uid();
				$result = Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'user_id', 'exam_time', 'examed', 'team'));
			} else {
            	// 指定人员，则修改测算状态
				$result = Xcon::setByUid('xcDataExam', compact('exam_time', 'examed'), $data_exam->uid);
			}

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
