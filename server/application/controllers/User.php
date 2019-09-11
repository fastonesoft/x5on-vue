<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                $result = Xcon::gets('xvUser');
                Xcon::json(Xcon::NO_ERROR, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function add()
    {

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
