<?php
/**
 * 管理后台路由
 * User: jackdou
 * Date: 19-6-26
 * Time: 下午2:15
 */

Route::group(['prefix' => 'management', 'middleware' => 'web'], function() {
    $namespacePrefix = "\\JackDou\\Management\\Http\\Controllers\\";
    Route::get('/{timezone?}', $namespacePrefix . "ManagementController@home");
});
