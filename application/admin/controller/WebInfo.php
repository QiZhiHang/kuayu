<?php

namespace app\admin\controller;
use app\admin\Base;
use app\admin\model\Config;
use app\admin\model\Login;
use think\Db;
use think\Request;


class WebInfo extends Base {

    /*网站配置信息 */
    public function index(){

        $config_info = new Config();
        $info = $config_info->get_config_info();
        $this->assign('info',$info);
        return $this->fetch();
    }

    public function doconfig(){

        $files = request()->file('web_img');
        $data = request()->post();
        if($files){

            $upimg = $files->validate(['size'=>156789,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            if(!$upimg){

                $this->error($upimg->getError());
            }
            $web_img = $upimg->getSaveName();
            $data['web_img'] = $web_img;
            $res = Db::name('admin_config')->where('id',1)->update($data);
            if($res){

                $this->success('更新成功');
            }else{

                $this->error('更新失败');
            }
        }else{
            unset($data['web_img']);
            $res = Db::name('admin_config')->where('id',1)->update($data);
            if($res){

                $this->success('更新成功');
            }else{

                $this->error('更新失败');
            }

        }

    }

    public function user(){
        $admin_info =  new Login();
        $info = $admin_info->get_admin_info();
        if(request()->isPost()){
            $data = request()->post();

             $rule = [
               'ad_username' => 'require|max:10',
               /*'password' =>'require|max:20',
               'password_confirm'=>'require|confirm',*/

           ];
             $message = [
                    'ad_username.require' =>'用户名不能为空',
                    'ad_username.max'=>'用户名不得超过10个字符',
                    /*'password.require'=>'密码不得为空',
                    'password.max'=>'密码不得超过20个字符',
                    'password_confirm.require'=>'确认密码不得为空',
                    'password_confirm.confirm'=>'两次密码不一致',*/

             ];
            if($data['ad_password'] != ''){

                if($data['ad_password'] != $data['password_confirm']){

                    $this->error('两次密码不一致');
                }else{

                    $data['ad_password'] = md5($data['ad_password']);

                }


            }else{

                unset($data['ad_password']);
                unset($data['password_confirm']);
            }
            $res =  $this->validate($data,$rule,$message);
            if(!$res){
                $this->error($res);
            }else{

                    $res1 = Db::name('admin_user')->where('ad_id',1)->update($data);
                    if($res1){

                        $this->success('更新成功');
                    }else{

                        $this->error('更新失败');
                    }

            }

        }else{
            $this->assign('info',$info);
            return $this->fetch();
        }

    }

    public function banner(){
        $config = new Config();
        $info = $config->get_banner();
        $this->assign('info',$info);
        return $this->fetch();
    }
    /*轮播图管理*/

    public function banner_add(){
        if(request()->isPost()){
            $data = request()->post();

            if(!is_numeric($data['sort'])){

                $this->error('排序值必须是数字');
            }
            if(isset($data['status'])){

                $data['status'] = 1;
            }else{
                $data['status'] = 0;
            }
            $img = request()->file('banner_img');
            if(!$img){

                $this->error('请上传图片');
            }
            $info = $img->validate(['size'=>2400000,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            if(!$info){
                $this->error($img->getError());

            }else{

                $data['banner_img'] = $info->getSaveName();

                $data['add_time'] = time();
               $res = Db::name('banner')->insert($data);
                if($res){

                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }

        }else{

            return $this->fetch();

        }

    }

    /*
     *
     * 删除banner图
     * */
    public function banner_del(){
            $id = intval(input('id'));
            $config = new Config();
            $res = $config->del_banner($id);
        if($res){

            $this->success('删除成功');
        }else{

            $this->error('删除失败');
        }

    }
    /*
     * 修改图片
     *
     * */
    public function banner_edit(){
        $info = new Config();
        if(request()->isPost()){
            $data = request()->post();
            if(isset($data['status'])){
                $data['status'] = 1;
            }else{
                $data['status'] = 0;
            }
            $img = request()->file('banner_img');
            if($img){
                $res = $img->validate(['size'=>2400000,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if(!$res){
                    $this->error($img->getError());
                }else{

                    $data['banner_img'] = $res->getSaveName();
                }
            }

            $res = $info->banner_edit($data,$data['id']);
            if($res){

                $this->success('修改成功',url('banner'));
            }else{

                $this->error('修改失败');
            }

        }else{
            $id = intval(input('id'));

            $res = $info::get_one_banner($id);
            $this->assign('res',$res);
            return $this->fetch();
        }
    }

}