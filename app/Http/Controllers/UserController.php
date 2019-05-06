<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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





}
