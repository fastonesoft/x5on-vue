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

    public function json($code, $data)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(
                json_encode(['code' => $code, 'data' => $data])
            );
    }

    /**
     * @return mixed
     * 这种方式可以解决axios提交的数据无法用$_POST接收的问题
     */
    public function params()
    {
        return json_decode($this->input->raw_input_stream, true);
    }

    public function param($key) {
        $params = $this->params();
        return $params[$key];
    }

}

class XC_Controller extends XBASE_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // 没有用户信息，跳转“登录”
        if ($this->userinfor === null) {
            $this->json(-1, '没有登录，无法操作！');
        } else {
            // 检测用户有没有操作权限
            $a = 1;
        }
    }
}
