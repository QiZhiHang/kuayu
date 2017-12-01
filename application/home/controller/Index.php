<?php

namespace app\home\controller;

use app\home\Base;

class Index extends Base {

    private $model_index;
    public function __construct()
    {
        /*实例化数据信息*/
        $this->model_index = new \app\home\model\Index();

    }

    public function index()
    {
       $info =  $this->model_index->get_user_info();
        return view('index');
    }
}