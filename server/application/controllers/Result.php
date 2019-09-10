<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 测算清单
                $result = Xcon::gets('xvDataDone');
                Xcon::json(0, $result);
            } catch (Exception $e) {
                Xcon::json(2, $e->getMessage());
            }
        });
    }

    public function add()
    {
        Xcon::loginCheck(function ($userinfor) {

        });
    }

    public function edit()
    {
        echo '  --------edit ---------';
    }

    public function del()
    {
        Xcon::loginCheck(function ($userinfor) {

        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 标的查询
                $area_id = Xcon::param('area_id');

                $result = Xcon::getsBy('xvDataDone', compact('area_id'));

                Xcon::json(0, $result);
            } catch (Exception $e) {
                Xcon::json(2, $e->getMessage());
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

                $result = Xcon::gets('xvDataDone');
                Xcon::json(0, $result);
            } catch (Exception $e) {
                Xcon::json(2, $e->getMessage());
            }
        });
    }

}
