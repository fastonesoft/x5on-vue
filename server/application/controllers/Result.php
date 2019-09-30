<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 协作清单，完成
            $result = Xcon::getsBy('xvData', 'backed=1');

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function count()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 协作清单，方式
            $params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
            $end = Xcon::array_key($params, 'end');
            $key = Xcon::array_key($params, 'key');
            $value = Xcon::array_key($params, 'value');

            $result = Xcon::getsBy('xvData', "backed=1 and create_time between '$begin' and '$end' and $key = '$value'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
