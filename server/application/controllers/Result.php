<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 协作成果
            $result = Xcon::gets('xvDataDone');
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 地区协作查询
            $params = Xcon::params();
            $area_id = Xcon::array_key($params, 'area_id');

            $result = Xcon::getsBy('xvDataDone', compact('area_id'));
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
