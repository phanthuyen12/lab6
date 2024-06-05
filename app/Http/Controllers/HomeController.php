<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){
        return view("admin/index");
    }
    public function view_category(){
        return view("admin/create_category");
    }
    public function create_category(Request $request){  }
    public function update_category(Request $request){  }
    public function delete_category(Request $request){ }
}
