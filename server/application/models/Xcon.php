<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xcon
{
    // 跨域支持，上传的时候要禁用
    const CROS = 1;

    public static function cros() {
        if (self::CROS) {
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Origin: http://localhost:8080');
        }
    }

    public static function login($that, $success, $fail) {
        // 登录 检测
//        if ($that.$userinfor !== null) {
//            // 权限检测
//        } else {
//
//        }
//
//
//
//        try {
//
//            // 成功
//            call_user_func($success, $userinfor);
//        } catch (Exception $e) {
//            // 检测异常
//            call_user_func($fail, ['code' => -1, 'data' => $e->getMessage()]);
//        }
    }


    public static function json($that, $code, $data)
    {
        return $that->output
            ->set_content_type('application/json')
            ->set_output(
                json_encode(['code' => $code, 'data' => $data])
            );
    }

    /**
     * @return mixed
     * 这种方式可以解决axios提交的数据无法用$_POST接收的问题
     */
    public static function params($that)
    {
        return json_decode($that->input->raw_input_stream, true);
    }

    public static function param($that, $key)
    {
        $params = $that->params();
        return $params[$key];
    }

    /**
     * @param $tableName
     * @return mixed
     * 数据库查询
     */
    public static function gets($that, $tableName)
    {
        $query = $that->db->get($tableName);
        return $query->result();
    }

    public static function gets_as_array($that, $tableName)
    {
        $query = $that->db->get($tableName);
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
    public static function wheres_query($that, $tableName, $condition, $orderby = '', $limit = null)
    {
        $that->db->order_by($orderby);
        return $that->db->get_where($tableName, $condition, $limit);
    }

    // 查询结果
    public static function wheres($that, $tableName, $condition, $orderby = '', $limit = null)
    {
        $query = self::wheres_query($that, $tableName, $condition, $orderby, $limit);
        return $query->result();
    }

    public static function get($that, $tableName, $condition)
    {
        $query = self::wheres_query($that, $tableName, $condition);
        return $query->row();
    }

    public static function getById($that, $tableName, $id)
    {
        $query = self::wheres_query($that, $tableName, compact('id'));
        return $query->row();
    }

    public static function getByUid($that, $tableName, $uid)
    {
        $query = self::wheres_query($that, $tableName, compact('uid'));
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
    public static function likes($that, $tableName, $condition, $likes, $orderby = '', $limit = null)
    {
        $that->db->like($likes);
        $query = self::wheres_query($that, $tableName, $condition, $orderby, $limit);
        return $query->result();
    }

    /**
     * @return mixed
     * 获取 uuid
     */
    public static function uid($that)
    {
        $query = $that->db->query('SELECT uuid() as uid');
        $row = $query->row_array();
        $uid = $row['uid'];
        return str_replace('-', '', $uid);
    }

    /**
     * @param $tableName
     * @param $values
     * 存储数据记录
     */
    public static function addTo($that, $tableName, $values)
    {
        $that->db->insert($tableName, $values);
    }

    /**
     * @param $tableName
     * @param $cols
     * @param $by
     * @return mixed
     * 更新数据记录
     */
    public static function setBy($that, $tableName, $cols, $by)
    {
        $that->db->update($tableName, $cols, $by);
        return $that->db->affected_rows();
    }

    public static function setById($that, $tableName, $cols, $id) {
        self::setBy($that, $tableName, $cols, compact('id'));
        return $that->db->affected_rows();
    }

    public static function setByUid($that, $tableName, $cols, $uid) {
        self::setBy($that, $tableName, $cols, compact('uid'));
        return $that->db->affected_rows();
    }

}