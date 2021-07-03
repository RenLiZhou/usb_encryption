<?php

namespace App\Http\Middleware;

use App\Exceptions\OrException;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class MerchantPermission
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
        //缓存版本权限
        $rules = Auth::guard('merchant')->user()->getMerchantRules();
        $route_path = explode('/', $request->path());
        $check_path = '/'.($route_path[0]??'').'/'.($route_path[1]??'');
        if (empty($rules) || !in_array($check_path, array_column($rules,'href'))) {
            if ($request->expectsJson()){
                throw new OrException(OrException::NOT_PERMISSION);
            }
            return redirect()->route('403');
        }

        return $next($request);
    }
}
