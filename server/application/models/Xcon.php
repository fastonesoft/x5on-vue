<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xcon
{
    // 跨域支持，上传的时候要禁用
    const CROS = 1;

    public static function cros()
    {
        if (self::CROS) {
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Origin: http://localhost:8080');
        }
    }

    public static function sess_set($key, $value) {
        $CI =& get_instance();
        $CI->session->set_userdata($key, $value);
    }

    public static function sess_unset($key) {
        $CI =& get_instance();
        $CI->session->unset_userdata($key);
    }

    public static function sess_destroy() {
        $CI =& get_instance();
        $CI->session->sess_destroy();
    }

    public static function loginCheck($success)
    {
        // 登录 检测
        $CI =& get_instance();
        $userinfor = $CI->userinfor;

        if ($userinfor !== null) {
            call_user_func($success, $userinfor);
        } else {
            $CI->xcon->json(-1, '没有登录，或登录状态已过期！');
        }
    }


    public static function json($code, $data)
    {
        $CI =& get_instance();
        return $CI->output
            ->set_content_type('application/json')
            ->set_output(
                json_encode(compact('code', 'data'))
            );
    }

    /**
     * @return mixed
     * 这种方式可以解决axios提交的数据无法用$_POST接收的问题
     */
    public static function params()
    {
        $CI =& get_instance();
        return json_decode($CI->input->raw_input_stream, true);
    }

    public static function params_obj()
    {
        $CI =& get_instance();
        return json_decode($CI->input->raw_input_stream);
    }

    public static function param($key)
    {
        $params = self::params();
        return $params[$key];
    }

    /**
     * @param $tableName
     * @return mixed
     * 数据库查询
     */
    public static function gets($tableName)
    {
        $CI =& get_instance();
        $query = $CI->db->get($tableName);
        return $query->result();
    }

    public static function gets_as_array($tableName)
    {
        $CI =& get_instance();
        $query = $CI->db->get($tableName);
        return $query->result_array();
    }

    /**
     * @param        $tableName
     * @param        $condition  array('name !=' => $name, 'id <' => $id, 'date >' => $date);
     * @param string $orderby
     * @param null   $limit
     * @return mixed
     * 条件查询
     */
    public static function wheres_query($tableName, $condition, $orderby = '', $limit = null)
    {
        $CI =& get_instance();
        $CI->db->order_by($orderby);
        return $CI->db->get_where($tableName, $condition, $limit);
    }

    // 查询结果
    public static function getsBy($tableName, $condition, $orderby = '', $limit = null)
    {
        $query = self::wheres_query($tableName, $condition, $orderby, $limit);
        return $query->result();
    }

    public static function get($tableName, $condition)
    {
        $query = self::wheres_query($tableName, $condition);
        return $query->row();
    }

    public static function getById($tableName, $id)
    {
        $query = self::wheres_query($tableName, compact('id'));
        return $query->row();
    }

    public static function getByUid($tableName, $uid)
    {
        $query = self::wheres_query($tableName, compact('uid'));
        return $query->row();
    }

    /**
     * @param        $tableName
     * @param        $condition
     * @param        $likes
     * @param string $orderby
     * @param null   $limit
     * @return mixed
     * 模糊查询
     */
    public static function likes($tableName, $condition, $likes, $orderby = '', $limit = null)
    {
        $CI =& get_instance();
        $CI->db->like($likes);
        $query = self::wheres_query($tableName, $condition, $orderby, $limit);
        return $query->result();
    }

    /**
     * @return mixed
     * 获取 uuid
     */
    public static function uid()
    {
        $CI =& get_instance();
        $query = $CI->db->query('SELECT uuid() as uid');
        $row = $query->row_array();
        $uid = $row['uid'];
        return str_replace('-', '', $uid);
    }

    /**
     * @param $tableName
     * @param $values
     * 存储数据记录
     */
    public static function add($tableName, $values)
    {
        $CI =& get_instance();
        $CI->db->insert($tableName, $values);
    }

    /**
     * @param $tableName
     * @param $cols
     * @param $by
     * @return mixed
     * 更新数据记录
     */
    public static function setBy($tableName, $cols, $by)
    {
        $CI =& get_instance();
        $CI->db->update($tableName, $cols, $by);
        return $CI->db->affected_rows();
    }

    public static function setById($tableName, $cols, $id)
    {
        $CI =& get_instance();
        self::setBy($tableName, $cols, compact('id'));
        return $CI->db->affected_rows();
    }

    public static function setByUid($tableName, $cols, $uid)
    {
        $CI =& get_instance();
        self::setBy($tableName, $cols, compact('uid'));
        return $CI->db->affected_rows();
    }

    public static function delBy($tableName, $condition) {
        $CI =& get_instance();
        $CI->db->delete($tableName, $condition);
        return $CI->db->affected_rows();
    }

    public static function delById($tableName, $id) {
        return self::delBy($tableName, compact('id'));
    }

    public static function delByUid($tableName, $uid) {
        return self::delBy($tableName, compact('uid'));
    }

}