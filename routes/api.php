<?php
Route::group(['namespace' => 'Api'], function () {

    //商家api

    Route::post('merchant/login', 'MerchantController@login');

    //需登录验证
    Route::group(['middleware' => ['refreshtoken']], function () {
        Route::group(['prefix' => 'merchant'], function () {
            Route::get('', 'MerchantController@getInfo');//获取商家信息
            Route::get('logout', 'MerchantController@logout');//退出
            Route::post('encryptionUsb', 'MerchantController@encryptionDisk');//加密
        });
    });


    //客户端api

    //U盘
    Route::group(['prefix' => 'client'], function () {
        Route::get('getUsbInfo', 'ClientController@getUsbInfo');//获取u盘信息
        Route::get('getUpdateList', 'ClientController@getUpdateList');//获取U盘文件更新信息
        Route::post('createUsbTrack', 'ClientController@createUsbTrack');//添加U盘轨迹
    });

});
