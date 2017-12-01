<?php

namespace app\admin\model;
use think\Db;
use think\Model;

class Login extends Model {


    public function get_admin_info(){

       $admin_info =  Db::name('admin_user')->where('ad_id',1)->find();
        return $admin_info;
    }
    /*更改登录错误次数*/

    public function change_error_num()
    {
        $info = $this->get_admin_info();
        $update_info = array(

            'ad_login_num' =>$info['ad_login_num'] + 1,
        );

       Db::name('admin_user')->where('ad_id',1)->update($update_info);


    }
}