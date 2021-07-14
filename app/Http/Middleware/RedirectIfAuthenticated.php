<?php

namespace App\Http\Middleware;

use App\Exceptions\OrException;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            if($guard == 'crm'){
                if ($request->expectsJson()){
                    return responseError('您未登录', OrException::NOT_LOGIN);
                }
                return redirect()->route('crm.login');  // 处理登录
            }elseif($guard == 'merchant'){
                if ($request->expectsJson()){
                    return responseError('您未登录', OrException::NOT_LOGIN);
                }
                return redirect()->route('merchant.login');  // 处理登录
            }else{
                return responseError('您未登录', OrException::NOT_LOGIN);
            }
        }

        return $next($request);
    }
}
