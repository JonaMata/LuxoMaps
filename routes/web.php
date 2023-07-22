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
        Route::prefix('stickers')->name('stickers.')->controller(\App\Http\Controllers\StickerController::class)->group(function() {
            Route::post('', 'create')->name('create');
            Route::delete('{sticker}', 'delete')->name('delete');
        });
    });
});

require __DIR__.'/auth.php';
