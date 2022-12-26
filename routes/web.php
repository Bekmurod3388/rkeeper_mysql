<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});


Auth::routes([
    'register' => false,
]);

Route::post('/home',[\App\Http\Controllers\HomeController::class,'index_post'])->name('search')->middleware('auth');
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Route::get('/waiters',[\App\Http\Controllers\WaiterController::class,'waiters'])->name('waiters')->middleware('auth');
Route::post('/waiters',[\App\Http\Controllers\WaiterController::class,'waiters_post'])->name('waiters_post')->middleware('auth');

Route::get('/savdo',[\App\Http\Controllers\PaybindingController::class,'savdo'])->name('savdo')->middleware('auth');
Route::post('/savdo',[\App\Http\Controllers\PaybindingController::class,'savdo_post'])->name('savdo_post')->middleware('auth');
