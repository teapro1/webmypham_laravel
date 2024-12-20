<?php

// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\AdminMiddleware as Middleware;
class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
    
        Log::info('Non-admin user attempted to access admin route: ' . Auth::user()->role);
    
        return redirect('/'); // Hoặc trang lỗi 403
    }
    
}
