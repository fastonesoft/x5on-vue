<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends XC_TOHOME_Controller {

	public function index()
	{
//		$this->load->view('login_view');
        $this->load->view('index.html');
	}

    public function login()
    {
        // 记录登录 用户信息
        $userinfor = ['id' => '001', 'name' => '石亮'];
        $this->session->set_userdata('XcSession', $userinfor);
        $this->load->view('js_to_home');
    }

}
