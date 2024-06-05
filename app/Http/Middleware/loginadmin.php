<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class loginadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('user')) { // Kiểm tra người dùng đã đăng nhập qua session
            $user = session()->get('user');
            if ($user && $user['role'] == 1) { // Kiểm tra role từ session
                return redirect('/admin/index');
            } else {
                // Người dùng đã đăng nhập nhưng không có quyền truy cập
                return $next($request); // Cho phép truy cập trang được yêu cầu
            }
        } else {
            // Người dùng chưa đăng nhập
            return $next($request); // Cho phép truy cập trang được yêu cầu
        }
    }
}
