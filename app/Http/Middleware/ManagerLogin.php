<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class ManagerLogin
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
        $info = Session::get('manager');
        if(!isset($info->id) || $info->id <=0)
        {
            return redirect('/manager/login/');
        }
        return $next($request);
    }
}
