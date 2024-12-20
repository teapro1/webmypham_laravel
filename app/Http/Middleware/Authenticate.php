<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\AdminMiddleware;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Nếu người dùng chưa xác thực và đang cố truy cập trang, chuyển hướng về trang login
        if (! $request->expectsJson()) {
            return route('welcome');
        }
    }
}
