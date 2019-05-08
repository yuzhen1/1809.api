<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class checkLoginToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //验证token是否有效
        $token = $_COOKIE['token']??'';
        $user_id = $_COOKIE['user_id']??'';
        if(empty($token) || empty($user_id)){
            $response=[
                'error'=>50030,
                'msg'=>'请先登录'
            ];
            header('refresh:3,url=http://passport.api.com/login/login');
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }
        if(!empty($token)){
            $key = "login_token:user_id:".$user_id;
            $token2 = redis::get($key);
            if($token!=$token2){
                die('token有误');
            }else{
                echo "登陆成功";
            }
        }else{
            header('refresh:3,url=http://passport.api.com/login/login');
            die('token有误，请重新登录');
        }
        return $next($request);
    }
}
