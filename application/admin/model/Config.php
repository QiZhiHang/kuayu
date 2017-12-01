<?php
namespace app\admin\model;


use think\Db;
use think\Model;

class Config extends Model {


    public function  get_config_info(){


       $config_info = Db::name('admin_config')->find();
        if(!empty($config_info)){

            return $config_info;
        }else{

            return false;
        }
    }

    public static function get_one_banner($id){

       $info = Db::name('banner')->where('id',$id)->find();
        if($info){

            return $info;
        }else{

            return false;
        }
    }
    public function get_banner(){

        $info = Db::name('banner')->order('sort desc')->paginate(10);
        if(!empty($info)){

            return $info;
        }else{
            return false;
        }

    }

    /*删除bannner 图*/

    public function del_banner($id){

        $info = Db::name('banner')->where('id',$id)->find();
        if(empty($info)){

            return false;
        }else{

            $res = Db::name('banner')->where('id',$id)->delete();
            if( $res ){
                @unlink('uploads/'.$info['banner_img']);
                return true;
            }else{

                return false;
            }
        }
    }

    /*
     * banner 修改
     * */

    public function banner_edit( $data ,$id )
    {

        $info = self::get_one_banner($id);
        if($info){
            unset($data['id']);
            $res = Db::name('banner')->where('id',$id)->update($data);
            if($res){
                if(isset($data['banner_img'])){

                    @unlink('uploads/'.$info['banner_img']);
                }
                return true;
            }else{
                return false;
            }


        }else{

            return false;
        }

    }
}