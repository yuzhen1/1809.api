<?php

namespace App\Http\Controllers;

use App\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    //链接另一个服务器的接口
    public function addUser(Request $request){
        $ch = curl_init();  //创建一个curl资源
        //设置url和对应的选项
        curl_setopt($ch, CURLOPT_URL, "http://1809api.test.com/test/add?user_id=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        //抓取url并提交给控制器
        $output = curl_exec($ch);
        $error_code = curl_errno($ch);//错误码
        //关闭url 释放系统资源
        curl_close($ch);
    }

    //post方式  form-data
    public function threePost(){
        $ch = curl_init();
        $arr=[
            'user_name'=>'lisi',
            'user_email'=>'lisi@qq.com'
        ];
        $post_json = json_encode($arr);
        curl_setopt($ch, CURLOPT_URL, "http://1809api.test.com/test/post_text");
        //将curl_exec获取的数据以文件流的形式返回
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //请求所携带的参数
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_json);
        $output = curl_exec($ch);
        print_r($output);
        curl_close($ch);
    }

    //curlpost方式传输数组
    public function threePost2(){
//        初始化
        $arr=[
            'user_name'=>'张三',
            'user_email'=>'zhangsan@qq.com',
        ];
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://1809api.test.com/test/post_text2");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
        $output = curl_exec($ch);
        print_r($output);
        curl_close($ch);
    }

    //curlpost方式传输application/x-www-form-urlencoded格式的数据
    public function threePost3(){
//        初始化
        $str="user_name=zhangsan&user_email=zhangsan@qq.com";
        $ch=curl_init();
        //初始化路径
        curl_setopt($ch, CURLOPT_URL, "http://1809api.test.com/test/post_text2");
        // 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //使用post方式请求
        curl_setopt($ch,CURLOPT_POST,1);
        //请求携带的参数
        curl_setopt($ch,CURLOPT_POSTFIELDS,$str);
        //获取信息
        $data=curl_exec($ch);
        print_r($data);
        curl_close($ch);
    }

    //请求中间件的测试方法
    public function mid(){
        echo "this is a way";
    }

    //注册
    public function register(Request $request){
        $password = $request->input('password');
        $password2 = $request->input('password2');
        $user_email = $request->input('user_email');

        //验证密码
        if($password!=$password2){
            $response=[
                'errno'=>50001,
                'msg'=>'两次密码输入不一致'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }

        //验证邮箱
        $u = UserModel::where(['user_email'=>$user_email])->first();
        if($u){
            $response=[
                'errno'=>'50010',
                'msg'=>'该邮箱已被注册'
            ];
            die(json_encode($response,JSON_UNESCAPED_UNICODE));
        }

        //密码加密
        $password=password_hash($password,PASSWORD_BCRYPT);
        $data = [
            'user_name'=>$request->input('user_name'),
            'user_email'=>$request->input('user_email'),
            'password'=>$password,
            'add_time'=>time(),
        ];
        //入库
        UserModel::insertGetId($data);

    }

    //登录
    public function login(Request $request){
        $password = $request->input('password');
        $user_email = $request->input('user_email');
        //验证邮箱
        $u = UserModel::where(['user_email'=>$user_email])->first();

        if($u){
            //存在  验证密码
            if(password_verify($password,$u->password)){
                $token = $this->getLoginToken($u->user_id);
                $key = "login_token:user_id:".$u->user_id;
                Redis::set($key,$token);
//                $get = Redis::get($key);
//                var_dump($get);die;
                Redis::expire($key,604800);
                //生成token
                $response=[
                    'errno'=>0,
                    'msg'=>'ok',
                    'data'=>[
                        'token'=>$token
                    ]
                ];

            }else{
                //登录失败
                $response=[
                    'errno'=>50003,
                    'msg'=>'密码不正确'
                ];
            }
        }else{
            $response=[
                'errno'=>50002,
                'msg'=>'该用户不存在'
            ];
        }
        die(json_encode($response,JSON_UNESCAPED_UNICODE));
    }

    //获取登录token
    public function getLoginToken($user_id){
        $rand_str = Str::random(10);
        $token = substr(md5($user_id.time().$rand_str),5,15);
        return $token;
    }

    //个人中心
    public function myCenter(){
        echo __METHOD__;
    }

}
