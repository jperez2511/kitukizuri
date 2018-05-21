<?php

Route::get('tururu', function () {
    dd('here kitukizuri');
});

Route::middleware('auth', 'kitukizuri')->group(['namespace' => 'Icebearsoft\Kitukizuri\Controllers'], function () {
    Route::resource('roles', 'RolesController');
    Route::resource('rolpermisos', 'RolesPermisosController', ['only'=>['index', 'store']]);
});


// Route::group(['prefix' => 'admin'], function() {

// });
