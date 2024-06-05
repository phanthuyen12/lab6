<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next): Response
    {
        if (session()->has("user")) {
            $user = session()->get("user");
            if ($user && $user['role']== 1) {
                return $next($request);
            } else {
                // User not authenticated with role 1
                return redirect('/admin/login');
            }
        } else {
            // User not authenticated
            return redirect('/admin/login');
        }
    
}
}