<?php
Route::group(['namespace' => 'Web'], function () {
    Route::get('forbidden', 'ForbiddenController@index')->name('403');
});
