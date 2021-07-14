<?php

namespace App\Http\Middleware;

use App\Exceptions\OrException;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CrmRbac
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
        $routeName = Route::currentRouteName();
        $rules = Auth::guard('crm')->user()->getAdminRules();
        if (!in_array($routeName, $rules)) {
            if ($request->expectsJson()){
                throw new OrException(OrException::NOT_PERMISSION);
            }
            return redirect()->route('403');
        }

        return $next($request);
    }
}
