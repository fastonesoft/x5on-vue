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
			// 获取查询日期
			$params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
			$end = Xcon::array_key($params, 'end');
			
			// 未分配任务清单（未完成期，全部可见）
			$result = Xcon::getsBy('xvData', "dataed=1 and back=0 and create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
		});
	}


}
