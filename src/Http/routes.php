<?php

Route::get('tururu', function () {
    dd('here kitukizuri');
});


Route::resource('roles', 'RolesController');

// Route::group(['prefix' => 'admin'], function() {

// });
