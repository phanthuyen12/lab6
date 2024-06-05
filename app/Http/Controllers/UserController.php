<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PasswordChanged;

class UserController extends Controller
{
    //
    public function index(){
        return view("admin/create_user");
    }
    public function create_user(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name'=>'required|string|max:255',
            'email' => 'required|string|email|unique:users', // Ensures unique email
            'phone' => 'required|string', // Adjust validation rules for phone number as needed
            'provincestore' => 'required|string', // Adjust validation rules as needed
            'districtstore' => 'required|string', // Adjust validation rules as needed
            'communestore' => 'required|string', // Adjust validation rules as needed
            'password' => 'required|string|min:8', // Enforces password confirmation
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $user = User::create([
            'full_name'=> $request->input('full_name'),
            'email' => $request->email,
            'phone' => $request->phone,
            'provincestore' => $request->provincestore,
            'districtstore' => $request->districtstore,
            'communestore' => $request->communestore,
            'role' => '1',
            'password' => Hash::make($request->password),
        ]);
        return response()->json(
            ['message'=>"thành công"
                ,'users'=>$user]);
        

    }
    public function get_login(Request $request){
        return view('admin/login');

    }public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
    
        if (Auth::attempt($credentials)) {
            if (Auth::user()->role == 2) {
                $user = Auth::user();
                $user_data = $user->toArray();
                session()->put('user', $user_data);
                return redirect()->intended('/admin/index');
            } else {
                return redirect()->back()->withErrors('Đăng nhập thất bại. Tài khoản của bạn không có quyền truy cập.');
            }
        } else {
            return redirect()->back()->withErrors('Email hoặc mật khẩu không chính xác');
        }
    }
    public function logout_admin(Request $request){
        $request->session()->forget('user');
        return redirect()->intended('/admin/login');



    }
    public function update(Request $request){

    }
    public function store(Request $request){ 

    }
    public function show(User $user){

    }
    public function edit(User $user){

    }
    public function updatePassword(Request $request){

    }
    public function destroy(User $user){

    }
    public function update_password(Request $request){
        $request->validate([
            'current_password' => ['required', 'string', 'min:8', 'max:255'],
            'new_password' => ['required', 'string', 'min:8', 'max:255'],
        ]);
    
        $userId = session('user')['user_id'] ?? null;
        if (!$userId) {
            return response()->json([
                'error' => true,
                'message' => 'Đăng nhập mới được đổi mật khẩu.'
            ]);
        }
    
        $user = User::where('user_id', $userId)->first();
    
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'không tìm thấy user'
            ]);
        }
    
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'error' => true,
                'message' => 'thất bại'
            ]);
        }
    
        $user->password = Hash::make($request->input('new_password'));
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'thành công'
        ]);
    }
    
        public function user_login(Request $request){
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
    
        if (Auth::attempt($credentials)) {
            if (Auth::user()->role == 1) {
                $user = Auth::user();
                $user_data = $user->toArray();
                session()->put('user', $user_data);
                return redirect()->intended('/');
            } else {
                return redirect()->back()->withErrors('Đăng nhập thất bại. Tài khoản của bạn không có quyền truy cập.');
            }
        } else {
            return redirect()->back()->withErrors('Email hoặc mật khẩu không chính xác');
        }
    }
    public function logout_user(Request $request){
        $request->session()->forget('user');
        return redirect()->intended('/login');



    }
}
