<?php

use App\Http\Controllers\wapp\UserController;
use Illuminate\Support\Facades\Route;

Route::post('getSessionKey',[UserController::class,'getSessionKey'])->name('wapp.getSessionKey');
Route::get('get_test',[UserController::class,'get_test'])->name('wapp.get_test');
Route::post('userLogin',[UserController::class,'userLogin'])->name('wapp.userLogin');
