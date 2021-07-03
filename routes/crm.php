<?php
Route::group(['namespace' => 'Crm', 'prefix' => 'crm'], function () {
    Route::get('/login', 'LoginController@index')->name('crm.login');
    Route::post('/login', 'LoginController@signIn')->name('crm.signin');

    Route::group(['middleware' => 'guest:crm'], function () {

        Route::get('', 'IndexController@index')->name('crm.main');
        Route::put('cache', 'IndexController@flushCache')->name('crm.cache.flush');
        Route::delete('cache', 'IndexController@cleanCache')->name('crm.cache.clean');
        Route::get('logout', 'LoginController@logout')->name('crm.logout');

        Route::group(['middleware' => ['crm.rbac','crm.adminlog']], function () {
            // 首页
            Route::get('first', 'IndexController@first')->name('crm.first');

            // CRM管理员
            Route::group(['prefix' => 'admin'], function () {
                Route::get('', 'AdminsController@index')->name('crm.admin.index');//列表
                Route::get('create', 'AdminsController@create')->name('crm.admin.create');//新建页面
                Route::get('{admin}/edit', 'AdminsController@edit')->name('crm.admin.edit');//编辑页面
                Route::post('', 'AdminsController@store')->name('crm.admin.store');//保存
                Route::patch('{admin}', 'AdminsController@update')->name('crm.admin.update');//更新
                Route::delete('{admin}', 'AdminsController@destroy')->name('crm.admin.destroy');//删除
                Route::patch('{admin}/active', 'AdminsController@updateStatus')->name('crm.admin.active');//更新状态

                Route::get('{admin}/password', 'AdminsController@editPassword')->name('crm.admin.password');//更新密码页面
                Route::patch('{admin}/password', 'AdminsController@updatePassword')->name('crm.admin.password');//更新密码

                Route::get('log', 'AdminLogController@index')->name('crm.admin.log');// 管理员日志
            });

            // CRM权限
            Route::group(['prefix' => 'rule'], function () {
                Route::get('', 'RulesController@index')->name('crm.rule.index');//列表
                Route::get('create', 'RulesController@create')->name('crm.rule.create');//新建页面
                Route::get('{rule}/edit', 'RulesController@edit')->name('crm.rule.edit');//编辑页面
                Route::post('', 'RulesController@store')->name('crm.rule.store');//保存
                Route::patch('{rule}', 'RulesController@update')->name('crm.rule.update');//更新
                Route::delete('{rule}', 'RulesController@destroy')->name('crm.rule.destroy');//删除
            });

            // CRM角色
            Route::group(['prefix' => 'role'], function () {
                Route::get('', 'RolesController@index')->name('crm.role.index');//列表
                Route::post('', 'RolesController@store')->name('crm.role.store');//保存
                Route::patch('{role}', 'RolesController@update')->name('crm.role.update');//更新
                Route::delete('{role}', 'RolesController@destroy')->name('crm.role.destroy');//删除
                Route::get('{role}/rule', 'RolesController@setRules')->name('crm.role.rule');//设置角色权限页面
                Route::patch('{role}/rule', 'RolesController@settedRules')->name('crm.role.rule.set');//更新角色权限
            });

            // 多语言
            Route::group(['prefix' => 'language'], function () {
                Route::get('', 'LanguageController@index')->name('crm.language.index');//列表
                Route::get('create', 'LanguageController@create')->name('crm.language.create');//新建页面
                Route::post('', 'LanguageController@store')->name('crm.language.store');//保存
                Route::get('{language}/edit', 'LanguageController@edit')->name('crm.language.edit');//编辑页面
                Route::patch('{language}', 'LanguageController@update')->name('crm.language.update');//更新
                Route::delete('{language}', 'LanguageController@destroy')->name('crm.language.destroy');//删除
            });

            // 激活码
            Route::group(['prefix' => 'activation_code'], function () {
                Route::get('', 'ActivationCodeController@index')->name('crm.activation_code.index');//列表
                Route::get('create', 'ActivationCodeController@create')->name('crm.activation_code.create');//新建页面
                Route::post('', 'ActivationCodeController@store')->name('crm.activation_code.store');//保存
                Route::delete('{activation_code}', 'ActivationCodeController@destroy')->name('crm.activation_code.destroy');//删除
                Route::get('batch_no/{batch_no}', 'ActivationCodeController@batchNo')->name('crm.activation_code.batch_no');//列表
            });

            // 设置
            Route::group(['prefix' => 'setting'], function () {
                Route::get('email', 'SettingController@email')->name('crm.setting.email');//邮件页面
                Route::post('email', 'SettingController@updateEmail')->name('crm.setting.email.update');//更新邮件
            });

            // 商户
            Route::group(['prefix' => 'merchant'], function () {

                Route::get('', 'MerchantsController@index')->name('crm.merchant.index');//列表
                Route::get('create', 'MerchantsController@create')->name('crm.merchant.create');//新建页面
                Route::get('{merchant}/edit', 'MerchantsController@edit')->name('crm.merchant.edit');//编辑页面
                Route::post('', 'MerchantsController@store')->name('crm.merchant.store');//保存
                Route::patch('{merchant}', 'MerchantsController@update')->name('crm.merchant.update');//更新
                Route::delete('{merchant}', 'MerchantsController@destroy')->name('crm.merchant.destroy');//删除
                Route::put('{merchant}/active', 'MerchantsController@updateStatus')->name('crm.merchant.active');//更新状态

                // 商户权限
                Route::group(['prefix' => 'rule'], function () {
                    Route::get('', 'MerchantRulesController@index')->name('crm.merchant.rule.index');//列表
                    Route::get('create', 'MerchantRulesController@create')->name('crm.merchant.rule.create');//新建页面
                    Route::get('{rule}/edit', 'MerchantRulesController@edit')->name('crm.merchant.rule.edit');//编辑页面
                    Route::post('', 'MerchantRulesController@store')->name('crm.merchant.rule.store');//保存
                    Route::patch('{rule}', 'MerchantRulesController@update')->name('crm.merchant.rule.update');//更新
                    Route::delete('{rule}', 'MerchantRulesController@destroy')->name('crm.merchant.rule.destroy');//删除
                });

                // 商户版本
                Route::group(['prefix' => 'version'], function () {
                    Route::get('', 'MerchantVersionController@index')->name('crm.merchant.version.index');//列表
                    Route::get('create', 'MerchantVersionController@create')->name('crm.merchant.version.create');//新建页面
                    Route::get('{version}/edit', 'MerchantVersionController@edit')->name('crm.merchant.version.edit');//编辑页面
                    Route::post('', 'MerchantVersionController@store')->name('crm.merchant.version.store');//保存
                    Route::patch('{version}', 'MerchantVersionController@update')->name('crm.merchant.version.update');//更新
                    Route::delete('{version}', 'MerchantVersionController@destroy')->name('crm.merchant.version.destroy');//删除

                    Route::get('{version}/rule', 'MerchantVersionController@setRules')->name('crm.merchant.version.rule');//设置角色权限页面
                    Route::patch('{version}/rule', 'MerchantVersionController@settedRules')->name('crm.merchant.version.rule.set');//更新角色权限
                });
            });
        });

    });
    Route::get('test', 'TestController@test')->name('test');
});
