<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CExamed extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
			// 获取用户信息
			$user_id = $userinfor->id;

			// 案件审批
			$begin = Xcon::date();
			$end = Xcon::date();
            $result = Xcon::getsBy('xvData', "counted=1 and teamed=0 and (find_in_set('$user_id', teamed_user_ids) or teamed_type=0) and create_time between '$begin' and '$end'");

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
            $result = Xcon::getsBy('xvData', "counted=1 and teamed=$teamed and (find_in_set('$user_id', teamed_user_ids) or teamed_type=0) and create_time between '$begin' and '$end'");

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

	public function pass()
	{
		Xcon::loginCheck(function ($userinfor) {
			// 审批通过
			$params = Xcon::params();
			$uid = Xcon::array_key($params, 'uid');

			// 检测审批标的是否存在
			$data = Xcon::checkByUid('xcData', $uid);
			$data_id = $data->id;

			// 提交审核
			$user_id = $userinfor->id;
			$exam_id = Xcon::EXAM_TEAMED;
			$exam_time = Xcon::datetime();
			$examed = 1;
			$team = 1;

			/**
			 * 没有指定审批人，则添加审批人信息
			 * 指定审批人，则变更审批记录
			 * data_id, teamed_type联合查询
			 */
			$teamed_type = 0;
			$id = $data_id;
			$data = Xcon::getBy('xvData', compact('id', 'teamed_type'));
			if ($data !== null) {
				// 没有指定审批人员，提单审批信息
				$uid = Xcon::uid();
				Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'user_id', 'exam_time', 'examed', 'team'));
			} else {
				// 指定审批人员，修改审批状态
				// 根据data_id, exam_id, user_id, => examed
				Xcon::setBy('xcDataExam', compact('exam_time', 'examed'), compact('data_id', 'exam_id', 'user_id'));
			}

			// 返回标的状态变更信息
			$result = Xcon::getById('xvData', $data_id);
			Xcon::json(Xcon::NO_ERROR, $result);
		});
	}

}
