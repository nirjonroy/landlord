<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\ContactPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomepageBannerController;
use App\Http\Controllers\Admin\PropertyManagementController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('site-logo', [HomeController::class, 'siteLogo'])->name('site.logo');
Route::get('homepage-banners/{homepageBanner}/image', [HomeController::class, 'homepageBannerImage'])->name('homepage-banners.image');
Route::get('about-page/{aboutPage}/image/{group}/{index?}', [HomeController::class, 'aboutPageImage'])->name('about.image');
Route::get('contact-page/{contactPage}/image/{group}/{index?}', [ContactController::class, 'image'])->name('contact.image');

Route::get('/dashboard', function () {
    return redirect()->to(route('profile.edit', ['tab' => 'dashboard']).'#dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('about-page', [AboutPageController::class, 'edit'])->name('about-page.edit');
    Route::put('about-page', [AboutPageController::class, 'update'])->name('about-page.update');
    Route::get('contact-page', [ContactPageController::class, 'edit'])->name('contact-page.edit');
    Route::put('contact-page', [ContactPageController::class, 'update'])->name('contact-page.update');
    Route::get('homepage-banners', [HomepageBannerController::class, 'index'])->name('homepage-banners.index');
    Route::post('homepage-banners', [HomepageBannerController::class, 'store'])->name('homepage-banners.store');
    Route::put('homepage-banners/{homepageBanner}', [HomepageBannerController::class, 'update'])->name('homepage-banners.update');
    Route::delete('homepage-banners/{homepageBanner}', [HomepageBannerController::class, 'destroy'])->name('homepage-banners.destroy');
    Route::get('users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('properties', [PropertyManagementController::class, 'index'])->name('properties.index');
    Route::put('properties/{property}/review', [PropertyManagementController::class, 'updateReview'])->name('properties.review.update');
    Route::get('site-info', [DashboardController::class, 'editSiteInfo'])->name('site-info.edit');
    Route::get('site-info/logo', [DashboardController::class, 'siteLogo'])->name('site-info.logo');
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
    Route::get('/profile/files/{type}/{index?}', [ProfileController::class, 'file'])->name('profile.files.show');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
});

require __DIR__.'/auth.php';
