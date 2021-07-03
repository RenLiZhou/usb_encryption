<?php

namespace App\Http\Middleware;

use App\Jobs\ProcessCrmAdminLog;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\CrmRule;

class CrmAdminLog
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
        $response = $next($request);

        if (config('services.crm_admin_log') === true) {
            $routeName = Route::currentRouteName();
            $admin_need_logs = Cache::remember('crm_admin_need_logs', 86400, function() {
                $needlogs = CrmRule::where('islog', 1)->where('type', 0)->select('rule')->get()->toArray();
                $admin_need_logs = [];
                foreach ($needlogs as $key => $value) {
                    $admin_need_logs[] = $value['rule'];
                }
                return $admin_need_logs;
            });
            if (in_array($routeName, $admin_need_logs)) {
                $data = [
                    'route_name' => $routeName,
                    'ip' => $request->getClientIp(),
                    'url' => $request->path(),
                    'method' => $request->getMethod(),
                    'param' => json_encode($request->all())
                ];
                ProcessCrmAdminLog::dispatch(\App\Models\CrmAdminLog::TYPE_BEHAVIOR, Auth::guard('crm')->id(), $data);
            }
        }

        return $response;
    }
}
