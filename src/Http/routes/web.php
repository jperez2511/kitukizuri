<?php

Route::middleware(['auth', 'kitukizuri'])->group(function () {
    Route::get('dashboard', 'Kitukizuri\DashboardController@index')->name('dashboard.index');
    Route::resource('roles', 'Kitukizuri\RolesController');
    Route::resource('modulos', 'Kitukizuri\ModulosController');
    Route::resource('usuarios', 'Kitukizuri\UsuariosController');
    Route::resource('asignarpermiso', 'Kitukizuri\UsuarioRolController');
    Route::resource('permisos', 'Kitukizuri\PermisosController', ['only'=>['index', 'store']]);
    Route::resource('rolpermisos', 'Kitukizuri\RolesPermisosController', ['only'=>['index', 'store']]);
    Route::resource('empresas', 'Kitukizuri\EmpresasController');
    Route::resource('moduloempresas', 'Kitukizuri\ModuloEmpresasController');
  });