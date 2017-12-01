<?php


namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;


class Login extends Controller  {
    private $admin_info;
    public function __construct(Request $request)
    {
        $this->admin_info= new \app\admin\model\Login();
        parent::__construct($request);
    }

    public function index(){

        $start = strtotime(date("Y-m-d"),time());
        $info = $this->admin_info->get_admin_info();
        if($info['ad_login_time'] < $start){

            Db::name('admin_user')->where('ad_id',1)->update(array('ad_login_time'=>time(),'ad_login_num'=>0));
        }
        return view('index');
    }

    public function dologin()
    {

        $input =  input('post.');
        $info = $this->admin_info->get_admin_info();
        if($info['ad_login_num'] >= 3){

            return json(array('status'=>-1,'msg'=>'您今天已登录错误超过三次'));
        }

        if($info['ad_password'] != md5($input['password'])){
            $this->admin_info->change_error_num();
            return json(array('status'=>-1,'msg'=>'密码错误'));
        }
        if($info['ad_username'] != $input['username']){
            $this->admin_info->change_error_num();
            return json(array('statu'=>-1,'msg'=>'账号错误'));
        }else{
            Session::set('admin_info2','haslogin');
            return json(array('status'=>1,'msg'=>'登录成功'));
        }



    }
    
    /*后台退出操作*/
    public function loginout()
    {
            Session::set('admin_info2',null);

            $this->redirect('/admin/login');

    }
}