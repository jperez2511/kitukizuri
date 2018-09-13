<?php

Route::get('/', 'DashboardController@index')->name('dashboard.index');
Route::resource('roles', 'RolesController');
Route::resource('modulos', 'ModulosController');
Route::resource('usuarios', 'UsuariosController');
Route::resource('asignarpermiso', 'UsuarioRolController');
Route::resource('permisos', 'PermisosController', ['only'=>['index', 'store']]);
Route::resource('rolpermisos', 'RolesPermisosController', ['only'=>['index', 'store']]);
Route::resource('empresas', 'EmpresasController');
Route::resource('moduloempresas', 'ModuloEmpresasController');