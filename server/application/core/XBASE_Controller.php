<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class XBASE_Controller extends CI_Controller
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

class XC_Controller extends XBASE_Controller {
    public function __construct()
    {
        parent::__construct();

        if ($this->userinfor === null) {
            redirect('/auth', 'refresh');
        }
    }
}

class XC_TOHOME_Controller extends XBASE_Controller {

    public function __construct()
    {
        parent::__construct();

        if ($this->userinfor !== null) {
            redirect('/', 'refresh');
        }
    }

}