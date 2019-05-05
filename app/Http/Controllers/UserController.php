<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function addUser(Request $request){
        $user_id = $request->input('user_id');
        echo $user_id;die;
    }
}
