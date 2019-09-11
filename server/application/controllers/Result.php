<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 协作成果
                $result = Xcon::gets('xvDataDone');
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
                // 地区协作查询
                $area_id = Xcon::param('area_id');

                $result = Xcon::getsBy('xvDataDone', compact('area_id'));
                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

}
