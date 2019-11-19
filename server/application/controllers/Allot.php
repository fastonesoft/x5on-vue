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

			// 二、未分配任务清单
			$datas = Xcon::getsBy('xvData', 'dataed=1 and alloted=0');

			// 三、系统案件统计
			$count = Xcon::getBy('xvDataCount', null);

			var_dump($count);
			Xcon::json(Xcon::NO_ERROR, compact('datas', 'users', 'count'));
        });
    }

	public function index1()
	{
		Xcon::loginCheck(function ($userinfor) {
			// 查询用户信息
			$id = $userinfor->id;
			$group_id = $userinfor->group_id;
			// 查询部门
			$user = Xcon::checkById('xvUser', $id);
			// 返回用户列表
			$part_id = $user->part_id;
			$result = Xcon::getsBy('xvUser', "part_id=$part_id and group_id<$group_id", 'group_id, id');
			Xcon::json(Xcon::NO_ERROR, $result);
		});
	}


}
