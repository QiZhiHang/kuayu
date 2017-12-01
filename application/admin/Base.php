<?php

namespace app\admin;
use think\Controller;
use think\Request;
use think\Session;

class Base extends Controller
{

    public function __construct(Request $request)
    {
        $this->isLogin();
        parent::__construct($request);
    }

    protected function isLogin(){

        if(!Session::has('admin_info2')){
            $this->redirect('/admin/login');

        }
    }

}