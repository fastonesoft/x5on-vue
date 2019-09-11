<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 标的清单
                $result = Xcon::gets('xvDataNotConfirm');
                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function add()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 标的清单添加
                $param = Xcon::params();

                // 增加 uid
                $uid = Xcon::uid();
                $param['uid'] = $uid;
                $param['create_time'] = date('Y-m-d');

                // 重复编号检测
                $id = $param['id'];
                Xcon::existById('xcData', $id);

                // 添加数据
                Xcon::add('xcData', $param);

                // 查询出添加数据
                $result = Xcon::getByUid('xvDataNotConfirm', $uid);

                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function edit()
    {
        echo '  --------edit ---------';
    }

    public function del()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 提交测算
                $uid_string = Xcon::param('uids');

                $uids = explode(',', $uid_string);
                foreach ($uids as $uid) {
                    Xcon::delByUid('xcData', $uid);
                }

                $result = Xcon::gets('xvDataNotConfirm');
                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 标的查询
                $begin = Xcon::param('begin');
                $end = Xcon::param('end');

                $result = Xcon::getsBy('xvDataNotConfirm', "create_time between '$begin' and '$end'");

                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function upto()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 提交测算
                $uid_string = Xcon::param('uids');

                $uids = explode(',', $uid_string);
                foreach ($uids as $uid) {
                    $confirm_user_id = $userinfor->id;
                    Xcon::setByUid('xcData', compact('confirm_user_id'), $uid);
                }

                $result = Xcon::gets('xvDataNotConfirm');
                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

}
