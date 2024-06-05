<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    //
    public function index(){
        return view("client/index");
    }
    public function login_user(){
        return view("client/login");
    }
    public function register_user(){
        return view("client/register");
    }
    public function user_user(){
        $user = session('user');
        
        return view("client/user",compact("user"));

    }
}
