<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class xcon
{
    // 跨域支持，上传的时候要禁用
    const CROS = 1;

    public static function cros() {
        if (self::CROS) {
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Origin: http://localhost:8080');
        }
    }

}