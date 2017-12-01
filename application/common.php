<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function dd()
{
    array_map(function ($x) {
        dump($x);
    }, func_get_args());

    die(1);
}

/**
 * 记录帐户变动
 * @param   int     $user_id        用户id
 * @param   float   $user_money     可用余额变动
 * @param   int     $pay_points     消费积分变动
 * @param   string  $desc    变动说明
 * @param int $order_id 订单id
 * @param string $order_sn 订单sn
 * @return  bool
 */
function accountLog($user_id, $user_money = 0,$pay_points = 0, $desc = '',$order_id = 0 ,$order_sn = ''){

    $account_log = array(
        'user_id'       => $user_id,
        'user_money'    => $user_money,
        'pay_points'    => $pay_points,

        'change_time'   => time(),
        'desc'   => $desc,
        'order_id' => $order_id,
        'order_sn' => $order_sn
    );
    /* 更新用户信息 */

    $update_data = array(
        'user_money'        => ['exp','user_money+'.$user_money],
        'pay_points'        => ['exp','pay_points+'.$pay_points],

    );
    if(($user_money+$pay_points) == 0)
        return false;
   $update = \think\Db::name('users')->where('user_id',$user_id)->update($update_data);

    if($update){
        \think\Db::name('account_log')->insert($account_log);
        return true;
    }else{
        return false;
    }
}