<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                $id = '001';
                $result = Xcon::existBy('xcTest', compact('id'));

                Xcon::json(Xcon::NO_ERROR, $result);

            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

}
