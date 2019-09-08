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

    public function param($key)
    {
        $params = $this->params();
        return $params[$key];
    }

    /**
     * @param $tableName
     * @return mixed
     * 数据库查询
     */
    public function gets($tableName)
    {
        $query = $this->db->get($tableName);
        return $query->result();
    }

    public function gets_as_array($tableName)
    {
        $query = $this->db->get($tableName);
        return $query->result_array();
    }

    /**
     * @param $tableName
     * @param $condition  array('name !=' => $name, 'id <' => $id, 'date >' => $date);
     * @param string $orderby
     * @param null $limit
     * @return mixed
     * 条件查询
     */
    public function wheres_query($tableName, $condition, $orderby = '', $limit = null)
    {
        $this->db->order_by($orderby);
        return $this->db->get_where($tableName, $condition, $limit);
    }

    // 查询结果
    public function wheres($tableName, $condition, $orderby = '', $limit = null)
    {
        $query = $this->wheres_query($tableName, $condition, $orderby, $limit);
        return $query->result();
    }

    public function get($tableName, $condition)
    {
        $query = $this->wheres_query($tableName, $condition);
        return $query->row();
    }

    public function getById($tableName, $id)
    {
        $query = $this->wheres_query($tableName, compact('id'));
        return $query->row();
    }

    public function getByUid($tableName, $uid)
    {
        $query = $this->wheres_query($tableName, compact('uid'));
        return $query->row();
    }

    /**
     * @param $tableName
     * @param $condition
     * @param $likes
     * @param string $orderby
     * @param null $limit
     * @return mixed
     * 模糊查询
     */
    public function likes($tableName, $condition, $likes, $orderby = '', $limit = null)
    {
        $this->db->like($likes);
        $query = $this->wheres_query($tableName, $condition, $orderby, $limit);
        return $query->result();
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
