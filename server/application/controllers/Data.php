<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends XC_Controller
{

    /**
     * 返回值：
     * 添加、修改、查询：对象、数组
     * ----
     * 删除、提交：个数
     */

    public function index()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 标的清单
            $datas = Xcon::getsBy('xvData', 'data=0');
            // 地区列表
            $areas = Xcon::gets('xvAreaTown');
            // 统计结果
            $count = Xcon::getBy('xvDataCount', null);
            Xcon::json(Xcon::NO_ERROR, compact('datas', 'areas', 'count'));
        });
    }

    public function add()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 标的清单添加
            $params = Xcon::params();

            // 增加 uid
            $uid = Xcon::uid();
            $params['uid'] = $uid;
            $params['create_time'] = date('Y-m-d');

            // 重复编号检测
            $id = $params['id'];
            $name = Xcon::array_key($params, 'name');
            Xcon::existById('xcData', $id);
            Xcon::existBy('xcData', compact('name'), '“标的名称”重复！');

            // 添加数据
            Xcon::add('xcData', $params);

            // 查询出添加数据
            $result = Xcon::getByUid('xvData', $uid);

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function edit()
    {
        Xcon::loginCheck(function ($userinfor) {
            $params = Xcon::params();
            $uid = Xcon::array_key($params, 'uid');

            // 修改
            Xcon::setByUid('xcData', $params, $uid);

            $result = Xcon::getByUid('xvData', $uid);
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function del()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 提交测算
            $params = Xcon::params();
            $uid_string = Xcon::array_key($params, 'uids');

            $uids = explode(',', $uid_string);
            $result = 0;
            foreach ($uids as $uid) {
                Xcon::delByUid('xcData', $uid);
                $result++;
            }

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function find()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 标的查询
            $params = Xcon::params();
            $begin = Xcon::array_key($params, 'begin');
            $end = Xcon::array_key($params, 'end');

            $result = Xcon::getsBy('xvData', "data = 0 and create_time between '$begin' and '$end'");

            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

    public function upto()
    {
        Xcon::loginCheck(function ($userinfor) {
            // 提交测算
            $params = Xcon::params();
            $uid_string = Xcon::array_key($params, 'uids');

            $result = 0;
            $uids = explode(',', $uid_string);
            foreach ($uids as $uid) {
                $user_id = $userinfor->id;
                $exam_id = Xcon::EXAM_DATA;
                $exam_time = date('Y-m-d H:i:s');

                // 检测标的是否存在
                $data = Xcon::checkByUid('xcData', $uid);
                $data_id = $data->id;

                // 检测标的是否已经提交审核
                Xcon::existBy('xcDataExam', compact('data_id', 'exam_id'), '“标的”已经提交审核！');

                // 提交
                $uid = Xcon::uid();
                Xcon::add('xcDataExam', compact('uid', 'data_id', 'exam_id', 'user_id', 'exam_time'));

                $result++;
            }
            Xcon::json(Xcon::NO_ERROR, $result);
        });
    }

}
