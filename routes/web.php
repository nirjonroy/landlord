<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('site-info', [DashboardController::class, 'editSiteInfo'])->name('site-info.edit');
    Route::get('api-access', [DashboardController::class, 'apiAccess'])->name('api-access.index');
    Route::get('staff', [RolePermissionController::class, 'staffIndex'])->name('staff.index');
    Route::get('staff/create', [RolePermissionController::class, 'createStaff'])->name('staff.create');
    Route::post('staff', [RolePermissionController::class, 'storeStaff'])->name('staff.store');
    Route::get('roles', [RolePermissionController::class, 'roles'])->name('roles.index');
    Route::get('permissions', [RolePermissionController::class, 'permissions'])->name('permissions.index');
    Route::get('roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
    Route::post('roles-permissions/permissions', [RolePermissionController::class, 'storePermission'])->name('roles-permissions.permissions.store');
    Route::post('roles-permissions/roles', [RolePermissionController::class, 'storeRole'])->name('roles-permissions.roles.store');
    Route::put('roles-permissions/roles/{role}/permissions', [RolePermissionController::class, 'updateRolePermissions'])->name('roles-permissions.roles.permissions.update');
    Route::put('roles-permissions/admins/{admin}/roles', [RolePermissionController::class, 'updateAdminRoles'])->name('roles-permissions.admins.roles.update');
    Route::put('site-info', [DashboardController::class, 'updateSiteInfo'])->name('site-info.update');
    Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
