<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class filter10
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
        $key = "filter10-number";
        Redis::incr($key);
        $num = Redis::get($key);
        if($num>10){
            die('请求次数超过限制');
        }
        echo $num;echo "<hr>";
        Redis::expire($key,60);
        return $next($request);
    }
}
