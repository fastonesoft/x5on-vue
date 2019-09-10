<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends XC_Controller
{

    public function index()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 测算清单
                $result = $this->xcon->gets('xvDataDone');
                $this->xcon->json(0, $result);
            } catch (Exception $e) {
                $this->xcon->json(2, $e->getMessage());
            }
        });
    }

    public function add()
    {
        $this->xcon->loginCheck(function ($userinfor) {

        });
    }

    public function edit()
    {
        echo '  --------edit ---------';
    }

    public function del()
    {
        $this->xcon->loginCheck(function ($userinfor) {

        });
    }

    public function find()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 标的查询
                $area_id = $this->xcon->param('area_id');

                $result = $this->xcon->getsBy('xvDataDone', compact('area_id'));

                $this->xcon->json(0, $result);
            } catch (Exception $e) {
                $this->xcon->json(2, $e->getMessage());
            }
        });
    }

    public function upto()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 提交测算
                $uid_string = $this->xcon->param('uids');

                $uids = explode(',', $uid_string);
                foreach ($uids as $uid) {
                    $confirm_user_id = $userinfor->id;
                    $this->xcon->setByUid('xcData', compact('confirm_user_id'), $uid);
                }

                $result = $this->xcon->gets('xvDataNotConfirm');
                $this->xcon->json(0, $result);
            } catch (Exception $e) {
                $this->xcon->json(2, $e->getMessage());
            }
        });
    }

}
