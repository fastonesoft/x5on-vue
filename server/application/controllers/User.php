<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends XC_Controller
{

    public function index()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                $result = $this->xcon->gets('xvUser');
                $this->xcon->json(0, $result);
            } catch (Exception $e) {
                $this->xcon->json(2, $e->getMessage());
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
