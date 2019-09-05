<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class XC_Controller extends CI_Controller
{

    // 用户信息
    public $userinfor = null;

    public function __construct()
    {
        parent::__construct();

        $this->userinfor = $this->session->userdata('XcSession');
    }

    public function json ($code, $data) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(
                json_encode(['code' => $code, 'data' => $data])
            );
    }

}
