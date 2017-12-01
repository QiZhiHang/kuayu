<?php

namespace app\admin\controller;
use app\admin\Base;


class Index extends Base {


    public function index(){

        return $this->fetch();
    }
}