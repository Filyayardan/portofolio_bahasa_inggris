<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'index'])->name('home');
Route::post('/contact', [SiteController::class, 'contact'])->middleware('throttle:10,1')->name('contact.store');
Route::get('/locale/{locale}', LocaleController::class)->name('locale');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'show'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::resource('tasks', TaskController::class)->except('show');
        Route::resource('skills', SkillController::class)->except('show');

        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
        Route::post('messages/{message}/read', [MessageController::class, 'markRead'])->name('messages.read');
        Route::post('messages/{message}/unread', [MessageController::class, 'markUnread'])->name('messages.unread');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});

// The guest middleware redirects authenticated users using the "login" route name.
Route::get('login', fn () => auth()->check()
    ? redirect()->route('admin.dashboard')
    : redirect()->route('admin.login'))->name('login');
