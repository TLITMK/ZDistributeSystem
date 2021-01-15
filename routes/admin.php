<?php


use App\Http\Controllers\admin\PermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;

Route::post('login', [UserController::class, 'login'])->name('admin.api.login');

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    Route::post('logout', [UserController::class, 'logout'])->name('admin.api.logout');

    Route::get('user/info', [UserController::class, 'userInfo'])->name('admin.api.userInfo');

    Route::post('permission/all',[PermissionController::class,'permission_all'])->name('permission.all');
    Route::post('permission/edit',[PermissionController::class,'permission_edit'])->name('permission.edit');
    Route::post('permission/del',[PermissionController::class,'permission_del'])->name('permission.del');

    Route::post('user/admin_list',[UserController::class,'admin_list'])->name('permission.admin_list');



});