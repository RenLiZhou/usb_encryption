<?php


namespace App\Http\Middleware;

use Closure;

/**
 * 语言切换中间件
 * Class Language
 * @package App\Http\Middleware
 */
class ApiLanguage
{

    /**
     * 根据请求参数自动切换语言包
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = $request->get('lang');
        if (!empty($lang) && file_exists(resource_path("lang/$lang"))){
            app()->setLocale($lang);
        }
        return $next($request);
    }

}
