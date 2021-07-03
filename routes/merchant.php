<?php
Route::group(['namespace' => 'Merchant', 'prefix' => 'merchant'], function () {
    Route::get('login', 'LoginController@index')->name('merchant.login');
    Route::post('login', 'LoginController@signIn')->name('merchant.signin');

    Route::group(['middleware' => ['guest:merchant','merchant.language']], function () {
        Route::get('logout', 'LoginController@logout')->name('merchant.logout');
        Route::get('index', 'indexController@index')->name('merchant.main');

        Route::get('overview', 'indexController@overview')->name('merchant.overview');  //概括

        Route::group(['middleware' => 'merchant.permission'], function () {
            //路由模块控制权限

            //U盘管理
            Route::group(['prefix' => 'disk'], function () {
                Route::get('', 'DisksController@index')->name('merchant.disk.index');   //列表
                Route::get('{disk}/edit', 'DisksController@edit')->name('merchant.disk.edit');    //编辑
                Route::patch('{disk}', 'DisksController@update')->name('merchant.disk.update'); //更新
                Route::put('{disk}/active', 'DisksController@updateStatus')->name('merchant.disk.active'); //启用禁用
                Route::post('bacth_active', 'DisksController@bacthUpdateStatus')->name('merchant.disk.active.bacth'); //批量启用禁用
                Route::get('{disk}/track', 'DisksController@track')->name('merchant.disk.track');   //轨迹
                Route::post('{disk}/track_empty', 'DisksController@emptyTrack')->name('merchant.disk.track.empty');   //清空轨迹
                Route::get('{disk}/track_export', 'DisksController@exportTrack')->name('merchant.disk.track.export');   //导出轨迹
            });


            //更新策略
            Route::group(['prefix' => 'strategy_update'], function () {
                Route::get('', 'StrategyUpdateController@index')->name('merchant.strategy_update.index');   //列表
                Route::get('create', 'StrategyUpdateController@create')->name('merchant.strategy_update.create');    //新建
                Route::post('', 'StrategyUpdateController@store')->name('merchant.strategy_update.store'); //保存
                Route::get('{strategy_update}', 'StrategyUpdateController@edit')->name('merchant.strategy_update.edit');    //编辑
                Route::patch('{strategy_update}', 'StrategyUpdateController@update')->name('merchant.strategy_update.update'); //更新
                Route::delete('{strategy_update}', 'StrategyUpdateController@destroy')->name('merchant.strategy_update.delete'); //删除
                Route::delete('delete/bacth', 'StrategyUpdateController@bacthDestroy')->name('merchant.strategy_update.delete.bacth'); //批量删除
            });

            //权限策略
            Route::group(['prefix' => 'strategy_auth'], function () {
                Route::get('', 'StrategyAuthController@index')->name('merchant.strategy_auth.index');   //列表
                Route::get('create', 'StrategyAuthController@create')->name('merchant.strategy_auth.create');    //新建
                Route::post('', 'StrategyAuthController@store')->name('merchant.strategy_auth.store'); //保存
                Route::get('{strategy_auth}', 'StrategyAuthController@edit')->name('merchant.strategy_auth.edit');    //编辑
                Route::patch('{strategy_auth}', 'StrategyAuthController@update')->name('merchant.strategy_auth.update'); //更新
                Route::delete('{strategy_auth}', 'StrategyAuthController@destroy')->name('merchant.strategy_auth.delete'); //删除
                Route::delete('delete/bacth', 'StrategyAuthController@bacthDestroy')->name('merchant.strategy_auth.delete.bacth'); //批量删除
            });

            //新增授权
            Route::group(['prefix' => 'authorization'], function () {
                Route::get('', 'AuthorizationController@index')->name('merchant.authorization.index');   //列表
                Route::post('exchange', 'AuthorizationController@exchange')->name('merchant.authorization.exchange');    //新建
            });

            //u盘加密记录
            Route::group(['prefix' => 'disk_encrypt_record'], function () {
                Route::get('', 'DiskEncryptRecordsController@index')->name('merchant.disk_encrypt_record.index');   //列表
            });

            //高级设置
            Route::group(['prefix' => 'merchant_setting'], function () {
                Route::get('', 'MerchantSettingController@index')->name('merchant.merchant_setting.index');   //设置
                Route::post('screen_recording', 'MerchantSettingController@setScreenRecording')->name('merchant.merchant_setting.screen_recording');   //保存设置
                Route::post('watermark', 'MerchantSettingController@setWatermark')->name('merchant.merchant_setting.watermark');   //保存设置
            });

            //文件管理
            Route::group(['prefix' => 'file'], function () {
                Route::get('', 'FilesController@index')->name('merchant.file.index');   //文件主页面
                Route::get('files/{type}', 'FilesController@folderOrFiles')->where('type', 'file|folder')->name('merchant.file.all_files');   //获取跟目录下所有文件
                Route::post('files', 'FilesController@files')->name('merchant.file.files');   //获取指定目录下一级所有文件
                Route::delete('delete_files', 'FilesController@deleteFiles')->name('merchant.file.delete_files'); //删除文件

                Route::post('move', 'FilesController@move')->name('merchant.file.move'); //移动文件
                Route::post('create_folder', 'FilesController@createFolder')->name('merchant.file.create_folder'); //新建文件夹
                Route::post('rename', 'FilesController@rename')->name('merchant.file.rename'); //重命名
            });
        });
    });
});
