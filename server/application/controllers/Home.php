<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends XC_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $this->json(1, $this->userinfor);
	}

	public function logout() {
        $this->session->sess_destroy();
        // 刷新
        $this->load->view('js_to_home');
    }
}
