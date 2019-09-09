<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Count extends XC_Controller
{

    public function index()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 测算清单
                $result = $this->xcon->gets('xvDataNotGuess');
                $this->xcon->json(0, $result);
            } catch (Exception $e) {
                $this->xcon->json(2, $e->getMessage());
            }
        });
    }

    public function add()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 标的清单添加
                $param = $this->xcon->params();

                // 增加 uid
                $uid = $this->xcon->uid();
                $param['uid'] = $uid;
                $param['create_time'] = date('Y-m-d');

                // 添加数据
                $this->xcon->add('xcData', $param);

                // 查询出添加数据
                $result = $this->xcon->getByUid('xvDataNotConfirm', $uid);

                $this->xcon->json(0, $result);
            } catch (Exception $e) {
                $this->xcon->json(2, $e->getMessage());
            }
        });
    }

    public function edit()
    {
        echo '  --------edit ---------';
    }

    public function del()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 提交测算
                $uid_string = $this->xcon->param('uids');

                $uids = explode(',', $uid_string);
                foreach ($uids as $uid) {
                    $this->xcon->delByUid('xcData', $uid);
                }

                $result = $this->xcon->gets('xvDataNotConfirm');
                $this->xcon->json(0, $result);
            } catch (Exception $e) {
                $this->xcon->json(2, $e->getMessage());
            }
        });
    }

    public function find()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 标的查询
                $begin = $this->xcon->param('begin');
                $end = $this->xcon->param('end');

                $result = $this->xcon->getsBy('xvDataNotGuess', "create_time between '$begin' and '$end'");

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
