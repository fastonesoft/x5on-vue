<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            $result = Xcon::gets('xcPart');

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
