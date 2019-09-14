<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                $items = Xcon::getsBy('xcRouter', null, 'type_id, ord');

                Xcon::json(Xcon::NO_ERROR, $items);

            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

}
