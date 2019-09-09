<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class A extends XC_Controller
{

    public function index()
    {
        $this->xcon->loginCheck(function ($userinfor) {
            try {
                // 标准格式
                throw new Exception('出错 测试');
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
