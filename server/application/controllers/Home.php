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
        Xcon::json(Xcon::NO_ERROR, $token);
    }

    // 登录
    public function login()
    {
        $id = Xcon::param('id');
        $pass = Xcon::param('pass');
        $token = Xcon::param('token');

        // 检测token是否正确
        if (md5(md5(session_id()) . $pass) !== $token) {
            Xcon::json(Xcon::ERROR_APP, '登录密码检验失败!');
            return;
        }

        // 检测用户
        $user = Xcon::getById('xcUser', $id);

        if ($user->id === $id && $user->pass === $pass) {
            // 一、记录登录 用户信息
            Xcon::sess_set('XcSession', $user);

            // 二、查询用户、权限，并返回客户端
            // 1、用户
            $user = Xcon::getById('xvUser', $id);
            // 2、权限

            Xcon::json(Xcon::NO_ERROR, $user);
        } else {
            Xcon::json(Xcon::ERROR_APP, '登录帐号、密码有误！');
        }

    }

    // 登录状态
    public function logstatus()
    {
        return $this->userinfor !== null;
    }

    // 退出
    public function logout()
    {
        Xcon::sess_destroy();
    }
}
