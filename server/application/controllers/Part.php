<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Part extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                $result = Xcon::gets('xcPart');

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
                $param = Xcon::params();
                $param['uid'] = Xcon::uid();

                // 重复检测
                Xcon::existById('xcPart', $param['id']);

                Xcon::add('xcPart', $param);
                $result = Xcon::getByUid('xcPart', $param['uid']);

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
                $param = Xcon::params();
                $result = Xcon::delByUid('xcPart', $param['uid']);

                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function find()
    {
        echo '  --------find -------------';
    }

}
