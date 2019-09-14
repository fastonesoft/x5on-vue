<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends XC_Controller
{

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 前端菜单分类
                $result = Xcon::gets('xcRouterType');
                Xcon::json(0, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

    public function items() {
        Xcon::loginCheck(function ($userinfor) {
            try {
                // 前端菜单
                // 以后要变成用户的菜单
                $result = Xcon::gets('xcRouter');
                Xcon::json(0, $result);
            } catch (Exception $e) {
                Xcon::json($e->getCode(), $e->getMessage());
            }
        });
    }

}
