<?php

namespace App\Http\Middleware;

use App\Exceptions\OrException;
use App\Models\Language;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MerchantLanguage
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
        $merchant = Auth::guard('merchant')->user();

        $merchant_language = Cache::remember("merchant_language", 7200, function () {
            return Language::query()->get()->pluck('name','id');
        });

        $lang = App::getLocale();
        if(!empty($merchant_language) && $merchant->lang_id && isset($merchant_language[$merchant->lang_id])){
            $lang = $merchant_language[$merchant->lang_id];
        }

        App::setLocale($lang);

        return $next($request);
    }
}
