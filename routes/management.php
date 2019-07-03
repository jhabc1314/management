<?php
/**
 * 管理后台路由
 * User: jackdou
 * Date: 19-6-26
 * Time: 下午2:15
 */
Route::group(['prefix' => 'management', 'middleware' => 'web'], function() {
    $namePrefix = "\\JackDou\\Management\\Http\\Controllers\\";
    Route::get('/', $namePrefix . "ManagementController@home")->name('management.home');

    //CRUD Server
    Route::resource('servers', $namePrefix . 'ServersController');

    Route::get('/servers/{id}/clients', $namePrefix . 'ServersController@clients')->name('clients.index');
    Route::post('/servers/{id}/clients', $namePrefix . 'ServersController@clientsStore')->name('clients.store');
    Route::get('/servers/{id}/clients/{cid}/push', $namePrefix . 'ServersController@clientsPush')->name('clients.push');
    Route::get('/servers/{id}/clients/{cid}/destroy', $namePrefix . 'ServersController@clientsDestroy')->name('clients.destroy');
    Route::get('/servers/{id}/clients/push_all', $namePrefix . 'ServersController@clientsPushAll')->name('clients.pushAll');
});
