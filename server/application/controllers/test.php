<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends XC_Controller
{

    public function index()
    {
		// 案件审批
		$begin = Xcon::date();
		$end = Xcon::date();
		$user_id = 'sw001';
		$result = Xcon::getsBy('xvData', "counted=1 and teamed=0 and FIND_IN_SET('$user_id', teamed_user_ids) and create_time between '$begin' and '$end'");

    	var_dump($result);
    }

}
