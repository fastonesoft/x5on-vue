<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allot extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
			// 一、返回部门用户列表
			$id = $userinfor->id;
			$group_id = $userinfor->group_id;
			$user = Xcon::checkById('xvUser', $id);

			$part_id = $user->part_id;
			$users = Xcon::getsBy('xvUser', "part_id=$part_id and group_id<$group_id", 'group_id, id');

			// 二、未分配任务清单（未完成期，全部可见）
			$begin = date('Y-m-d');
            $end = date('Y-m-d');
			$datas = Xcon::getsBy('xvData', "dataed=1 and back=0 and create_time between '$begin' and '$end'");

			// 三、系统案件统计
			$count = Xcon::getBy('xvDataCount', null);

			Xcon::json(Xcon::NO_ERROR, compact('datas', 'users', 'count'));
        });
    }

	public function find()
	{
		Xcon::loginCheck(function ($userinfor) {
            /**
             * 获取查询日期
             * 未分配任务清单（未完成期，全部可见）
             */
			$params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
			$end = Xcon::array_key($params, 'end');

			$result = Xcon::getsBy('xvData', "dataed=1 and back=0 and create_time between '$begin' and '$end'");
            Xcon::json(Xcon::NO_ERROR, $result);
		});
	}

	public function exec()
	{
		Xcon::loginCheck(function ($userinfor) {
			/**
			 * 执行分配
			 * 一、添加分配人员记录
			 * 二、设置任务为已执行状态
			 * 可能涉及的参数：
			 * uid、data_id、exam_id、team、user_id
			 */

			$team = 1;
			$exam_id = Xcon::EXAM_ALLOTED;
			$user_id = $userinfor->id;
			$examed = 1;

			// 检测标的编号
			$params = Xcon::params();
			$uid = Xcon::array_key($params, 'uid');
			$data_id = Xcon::checkIdByUid('xcData', $uid);

			// 检测分配记录是否存在
			Xcon::existBy('xcDataExam', compact('data_id', 'exam_id', 'team'), '任务已分配，无须重复');

			// 添加执行记录
			$uid = Xcon::uid();
			Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'team', 'user_id', 'examed'));

			// 返回添加的数据记录
			$result = Xcon::getById('xvData', $data_id);

			Xcon::json(Xcon::NO_ERROR, $result);
		});
	}

    public function user()
    {
        Xcon::loginCheck(function ($userinfor) {
            /**
             * 根据标的编号，检测用户分配情况
             * 涉及：
             * 一、标的编号获取
             * 二、查询用户分配情况
             */

            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            $result = Xcon::checkByUid('xvDataExamUserId', $uid);

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

	public function exam()
	{
		Xcon::loginCheck(function ($userinfor) {
			/**
			 * 任务分配单人员添加
			 * 涉及：
			 * data_id, exam_id, user_id, team, examed = 0,
			 * data_id, exam_id,
			 * 需要注意的是：已经执行过的，不可修改
			 */
			$params = Xcon::params();
			$uid = Xcon::array_key($params, 'uid');
			$exam_id = Xcon::array_key($params, 'exam_id');
			$user_id = Xcon::array_key($params, 'user_id');
			$team = 1;

			$data_id = Xcon::checkIdByUid('xcData', $uid);
			// 如果当前任务已通过审核，则不能修改
			$examed = 1;
			Xcon::existBy('xcDataExam', compact('data_id', 'exam_id', 'team', 'examed'));

			// 没有通过审核，删除当前记录，添加用户记录
			Xcon::delBy('xcDataExam', compact('data_id', 'exam_id'));
			// add
			$uid = Xcon::uid();
			$exam_time = Xcon::datetime();
			$examed = 0;
			Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'user_id', 'exam_time', 'examed'));

			$result = Xcon::getById('xvData', $data_id);
			Xcon::json(Xcon::NO_ERROR, $result);
		});
	}

	public function team()
	{
		Xcon::loginCheck(function ($userinfor) {
			/**
			 * 任务分配多人员添加
			 * 涉及：
			 * data_id, exam_id, user_id，is_add,
			 * data_id, exam_id,
			 * 需要注意的是：已经执行过的，不可修改
			 * 根据is_add真假来确定是否增加、删除
			 */

			$params = Xcon::params();
			$uid = Xcon::array_key($params, 'uid');
			$exam_id = Xcon::array_key($params, 'exam_id');
			$user_id = Xcon::array_key($params, 'user_id');
			$is_add = Xcon::array_key($params, 'is_add');

			$data_id = Xcon::checkIdByUid('xcData', $uid);
			if ($is_add) {
				// 存在，给出错误提示
				Xcon::existBy('xcDataExam', compact('data_id', 'exam_id', 'user_id'), '审批用户已设置');
				// 不存在，添加
				$team = Xcon::max('xcDataExam', 'team', compact('data_id', 'exam_id'))->max_value;
				$team++;

				$exam_time = Xcon::datetime();
				$examed = 0;

				$uid = Xcon::uid();
				Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'user_id', 'team', 'exam_time', 'examed'));
				$result = Xcon::getById('xvData', $data_id);
			} else {
				// 通过审核，不删除
				$examed = 1;
				Xcon::existBy('xcDataExam', compact('data_id', 'exam_id', 'user_id', 'examed'), '已确认审批，不能删除');
				// 没有通过，删除
				Xcon::delBy('xcDataExam', compact('data_id', 'exam_id', 'user_id'));
				$result = Xcon::getById('xvData', $data_id);
			}

			Xcon::json(Xcon::NO_ERROR, $result);
		});
	}

}
