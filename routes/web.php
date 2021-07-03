<?php
Route::group(['namespace' => 'Home'], function () {
    Route::get('forbidden', 'ForbiddenController@index')->name('403');
});
