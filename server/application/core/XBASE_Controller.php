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

        // 跨域测试，发布的时候禁用
        $this->xcon->cros();
    }
}

class XC_Controller extends XBASE_Controller
{
    public function __construct()
    {
        parent::__construct();

        // 获取控制器名
        $action = $this->router->fetch_class();
        // 获取方法
        $method = $this->router->fetch_method();

        // 提交数据库，保存
        $id = $action . '/' . $method;
        $uid = $this->xcon->uid();

        $role = $this->xcon->getById('xcAction', $id);
        if (!$role) {
            $this->xcon->add('xcAction', compact('id', 'uid', 'action', 'method'));
        }
    }

}
