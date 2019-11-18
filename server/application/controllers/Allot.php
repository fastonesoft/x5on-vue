<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allot extends XC_Controller
{

    public function index()
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
