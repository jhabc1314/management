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

    //CRUD Server-Client
    Route::get('/servers/{id}/clients', $namePrefix . 'ServersController@clients')->name('clients.index');
    Route::post('/servers/{id}/clients', $namePrefix . 'ServersController@clientsStore')->name('clients.store');
    Route::get('/servers/{id}/clients/{cid}/push', $namePrefix . 'ServersController@clientsPush')->name('clients.push');
    Route::get('/servers/{id}/clients/{cid}/destroy', $namePrefix . 'ServersController@clientsDestroy')->name('clients.destroy');
    Route::get('/servers/{id}/clients/push_all', $namePrefix . 'ServersController@clientsPushAll')->name('clients.pushAll');

    //CRUD Server-Supervisor
    Route::get('/servers/{id}/supervisor', $namePrefix . 'SupervisorController@supervisor')->name('supervisor.index');
    Route::post('/servers/{id}/supervisor', $namePrefix . 'SupervisorController@store')->name('supervisor.store');
    Route::get('/servers/{id}/supervisor/{ip}/push', $namePrefix . 'SupervisorController@push')->name('supervisor.push');
    Route::get('/servers/{id}/supervisor/push_all', $namePrefix . 'SupervisorController@pushAll')->name('supervisor.pushAll');
    Route::get('/servers/{id}/supervisor/{ip}/offline', $namePrefix . 'SupervisorController@offline')->name('supervisor.offline');
    Route::get('/servers/{id}/supervisor/{ip}/restart', $namePrefix . 'SupervisorController@restart')->name('supervisor.restart');
    Route::get('/servers/{id}/supervisor/{ip}/start', $namePrefix . 'SupervisorController@start')->name('supervisor.start');
    Route::get('/servers/{id}/supervisor/{ip}/stop', $namePrefix . 'SupervisorController@stop')->name('supervisor.stop');
    Route::get('/servers/{id}/supervisor/{ip}/online', $namePrefix . 'SupervisorController@online')->name('supervisor.online');

    //CRUD Crontab
    Route::resource('crontab', $namePrefix . 'CrontabController');

    Route::get('/crontab/{id}/start', $namePrefix . 'CrontabController@start')->name('crontab.start');
    Route::get('/crontab/{id}/stop', $namePrefix . 'CrontabController@stop')->name('crontab.stop');

});
