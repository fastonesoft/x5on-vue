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
        $uid = $this->xcon->uid($this);

        $role = $this->xcon->getById($this, 'xcAction', $id);
        if (!$role) {
            $this->xcon->addTo($this, 'xcAction', compact('id', 'uid', 'action', 'method'));
        } else {
            $name = '222222222';
            $this->xcon->setBy($this, 'xcAction', compact('name'),  compact('action'));
        }
    }

}
