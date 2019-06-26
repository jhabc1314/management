<?php
/**
 * Created by PhpStorm.
 * User: jackdou
 * Date: 19-6-26
 * Time: 下午2:15
 */

Route::group(['prefix' => 'management'], function() {
    $namespacePrefix = "\\JackDou\\Management\\Http\\Controllers\\";
    Route::get('/{timezone?}', $namespacePrefix . "ManagementController@home");
});
