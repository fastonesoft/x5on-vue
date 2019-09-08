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

}