<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class XcBase_Controller extends CI_Controller
{
    // 用户信息
    public $userinfor = null;

    public function __construct()
    {
        parent::__construct();

        $this->userinfor = $this->session->userdata('XcSession');
    }

}