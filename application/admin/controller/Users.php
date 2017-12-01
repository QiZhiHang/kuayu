<?php

namespace app\admin\controller;

use app\admin\Base;

class Users extends Base {


    public function index(){


       return $this->fetch();
    }
}