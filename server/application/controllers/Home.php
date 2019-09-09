<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends XBASE_Controller
{

    // 首页内容
    public function index()
    {
        $this->load->view('home.html');
    }

    // 返回当前页面的状态编号
    public function token()
    {
        $token = md5(session_id());
        $this->xcon->json(0, $token);
    }

    // 登录
    public function login()
    {
        $id = $this->xcon->param('id');
        $pass = $this->xcon->param('pass');
        $token = $this->xcon->param('token');

        // 检测token是否正确
        if (md5(md5(session_id()) . $pass) !== $token) {
            $this->xcon->json(1, md5(session_id()) . '登录密码检验失败!');
            return;
        }

        // 检测用户
        $user = $this->xcon->getById('xcUser', $id);

        if ($user->id === $id && $user->pass === $pass) {
            // 一、记录登录 用户信息
            $this->session->set_userdata('XcSession', $user);

            // 二、查询用户、权限，并返回客户端
            // 1、用户
            $user = $this->xcon->getById('xvUser', $id);
            // 2、权限

            $this->xcon->json(0, $user);
        } else {
            $this->xcon->json(1, '登录帐号、密码有误！');
        }

    }

    // 登录状态
    public function logstatus() {
        return $this->userinfor !== null;
    }

    // 退出
    public function logout()
    {
        $this->session->sess_destroy();
    }
}
