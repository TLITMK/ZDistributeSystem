<?php


use App\Http\Controllers\admin\PermissionController;
use App\Http\Controllers\admin\RoleController;
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
    Route::post('user/admin_edit',[UserController::class,'admin_edit'])->name('permission.admin_edit');
    Route::post('user/admin_del',[UserController::class,'admin_del'])->name('permission.admin_del');
    Route::post('user/admin_upload_avatar',[UserController::class,'admin_upload_avatar'])->name('permission.admin_upload_avatar');
    Route::post('user/set_roles',[UserController::class,'setRoles'])->name('permission.set_roles');

    Route::post('role/list',[RoleController::class,'getList'])->name('role.index');
    Route::post('role/edit',[RoleController::class,'edit'])->name('role.edit');
    Route::post('role/del',[RoleController::class,'del'])->name('role.del');
    Route::post('role/set_permissions',[RoleController::class,'setPermission'])->name('role.set_permissions');



});