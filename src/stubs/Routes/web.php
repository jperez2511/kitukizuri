<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
// Automatic injection controllers don't remove this line

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified', 'kitukizuri', 'kmenu', 'klang'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home.index');
    // Automatic injection routes don't remove this line
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('language/{lang}', [HomeController::class, 'setLanguage'])->name('home.language');
});