<?php

Route::get('tururu', function () {
    dd('here kitukizuri');
});

Route::group(['namespace' => 'Icebearsoft\Kitukizuri\Controllers', 'middleware' => ['auth', 'kitukizuri']], function () {
    Route::resource('roles', 'RolesController');
    Route::resource('rolpermisos', 'RolesPermisosController', ['only'=>['index', 'store']]);
});


// Route::group(['prefix' => 'admin'], function() {

// });
