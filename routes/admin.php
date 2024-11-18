<?php

use App\Http\Controllers\Admin\CrudGeneratorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PermissionGroupController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoleHasPermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin-dashboard');

Route::resource('user', UserController::class);
Route::get('user-list', [UserController::class, 'getDatatables'])->name('user-list');

Route::resource('role', RoleController::class);
Route::get('role-list', [RoleController::class, 'getDatatables'])->name('role-list');

Route::resource('permissiongroup', PermissionGroupController::class);
Route::get('permissiongroup-list', [PermissionGroupController::class, 'getDatatables'])->name('permissiongroup-list');

Route::resource('permission', PermissionController::class);
Route::get('permission-list', [PermissionController::class, 'getDatatables'])->name('permission-list');

Route::resource('rolehaspermission', RoleHasPermissionController::class);
Route::get('rolehaspermission-list', [RoleHasPermissionController::class, 'getDatatables'])->name('rolehaspermission-list');

Route::resource('language', LanguageController::class);
Route::get('language-list', [LanguageController::class, 'getDatatables'])->name('language-list');

Route::get('/crud-generator', [CrudGeneratorController::class, 'showCurdAndAPIGeneratorForm'])->name('crud.generator.create');
Route::post('/crud-generator', [CrudGeneratorController::class, 'generateCurdAndAPI'])->name('crud.generator.store');