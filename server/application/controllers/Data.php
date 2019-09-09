<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends XC_Controller
{

    public function index()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 标的清单
                $confirmed = 0;
                $result = $this->xcon->getsBy('xvData', compact('confirmed'));
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
                $result = $this->xcon->getByUid('xvData', $uid);

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
        echo '  --------edit ---------';
    }

    public function find()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 标的查询
                $begin = $this->xcon->param('begin');
                $end = $this->xcon->param('end');

                $result = $this->xcon->getsBy('xvData', "create_time between '$begin' and '$end'");

                $this->xcon->json(0, $result);
            } catch (Exception $e) {
                $this->xcon->json(2, $e->getMessage());
            }
        });
    }

}
