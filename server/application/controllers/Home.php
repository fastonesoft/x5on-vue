<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends XBASE_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     *
     * 基本数据库操作
     * $result = $this->wheres('xvuser', compact('id'));
     * $result = $this->get('xvuser', compact('id'));
     * $result = $this->getById('xvuser', 'admin');
     * $result = $this->getByUid('xvuser', '5a400a2fd1d711e9880e70f395158687');
     * $result = $this->likes('xvuser', "id != 'admin'", compact('id'));
     */

    public function index()
    {
        $this->load->view('home.html');
    }

    public function token()
    {
        $this->xcon->cros();

        $token = md5(session_id());
        $this->json(0, $token);
    }

    public function login()
    {
        $this->xcon->cros();

        $id = $this->param('id');
        $pass = $this->param('pass');
        $token = $this->param('token');

        // 检测token是否正确
        if (md5(md5(session_id()) . $pass) !== $token) {
            $this->json(-1, md5(session_id()) . '登录密码检验失败!');
            return;
        }

        // 检测用户
        $user = $this->getById('xcUser', $id);

        if ($user->id === $id && $user->pass === $pass) {

            // 一、记录登录 用户信息
            $this->session->set_userdata('XcSession', $user);

            // 二、查询用户权限
            $user = $this->getById('xvUser', $id);

            $this->json(0, $user);
        } else {
            $this->json(-1, '登录帐号、密码有误！');
        }

    }

    public function logout()
    {
        $this->xcon->cros();

        $this->session->sess_destroy();
    }
}
