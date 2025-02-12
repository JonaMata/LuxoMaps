<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [\App\Http\Controllers\StickerController::class, 'show'])->name('home');

Route::get('embed', [\App\Http\Controllers\StickerController::class, 'embed'])->name('stickers.embed');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('verified')->group(function() {
        Route::prefix('captcha')->name('captcha.')->controller(\App\Http\Controllers\PikBalController::class)->group(function() {
            Route::get('', 'show')->name('show');
            Route::get('get', 'get')->name('get');
            Route::get('image/{pikbal}', 'image')->name('image');
            Route::post('check', 'check')->name('check');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
        });
        Route::get('ap', 'App\Http\Controllers\PikBalController@create')->name('ap');

        Route::prefix('stickers')->name('stickers.')->controller(\App\Http\Controllers\StickerController::class)->group(function() {
            Route::post('', 'create')->name('create');
            Route::delete('{sticker}', 'delete')->name('delete');
        });
        Route::prefix('peertjes')->name('peertjes.')->controller(\App\Http\Controllers\PeertjesController::class)->group(function() {
            Route::get('', 'index')->name('index');
            Route::get('list', 'list')->name('list');
            Route::get('show/{peertje}', 'show')->name('show');
            Route::post('update/{peertje}', 'update')->name('update');
            Route::post('create', 'create')->name('create');
            Route::post('destroy', 'destroy')->name('destroy');
        });

        //Role routes
        Route::resource('roles', \App\Http\Controllers\RoleController::class);
        Route::prefix('roles')->name('roles.')->middleware('can:manage-roles')->controller(\App\Http\Controllers\RoleController::class)->group(function() {
            Route::get('{role}/assign/{user}', 'assign')->name('assign');
            Route::get('{role}/revoke/{user}', 'revoke')->name('revoke');
        });

        Route::resource('permissions', \App\Http\Controllers\PermissionController::class);
    });
});

require __DIR__.'/auth.php';
