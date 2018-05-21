<?php

Route::get('tururu', function () {
    dd('here kitukizuri');
});


Route::resource('roles', 'Icebearsoft\Kitukizuri\Controllers\RolesController');

// Route::group(['prefix' => 'admin'], function() {

// });
