<?php
/**
 * 管理后台路由
 * User: jackdou
 * Date: 19-6-26
 * Time: 下午2:15
 */
$guard = config('management.guard') ?: 'web';
Route::group(['prefix' => 'management', 'middleware' => 'web', 'auth' => 'auth:' . $guard], function() {
    $namespacePrefix = "\\JackDou\\Management\\Http\\Controllers\\";
    Route::get('/', $namespacePrefix . "ManagementController@home")->name('management.home');

    //CRUD Server
    Route::resource('servers', $namespacePrefix . 'ServersController');

    Route::get('/servers/{id}/clients', $namespacePrefix . 'ServerController@clients')->name('clients.index');
    Route::post('/servers/{id}/clients', $namespacePrefix . 'ServerController@clientsStore')->name('clients.store');
});
