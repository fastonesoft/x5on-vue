<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class A extends XC_Controller
{

    public function index()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 模板
                $result = 0;
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
