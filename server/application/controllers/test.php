<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class test extends XC_Controller
{

    public function index()
    {
    	$data_id = '2019001';
    	$result = Xcon::max('xcDataExam', 'team');
    	var_dump($result);
    }

}
