<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class A extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 模板
                $result = 0;
                Xcon::json(0, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function add()
    {

        Xcon::uid();



        echo '---------------';

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
        echo '  --------find -------------';
    }

}
