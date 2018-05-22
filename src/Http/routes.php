<?php

Route::middleware(['kitukizuri'])->group(function () {
    Route::group(['namespace' => 'Kitukizuri'], function () {
        Route::resource('roles', 'RolesController');
        Route::resource('rolpermisos', 'RolesPermisosController', ['only'=>['index', 'store']]);
    });
});
