<?php

namespace App\Providers;

use App\Models\CrmRule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //数据库字段长度
        Schema::defaultStringLength(191);

        //自定义标签
        Blade::if('crm_permission', function ($rulestr) {
            return (new CrmRule())->permissionHidden($rulestr);
        });
    }
}
