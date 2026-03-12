<?php

use App\Http\Controllers\NotificationsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('contact', 'contact')->name('contact');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', 'settings/profile')->name('dashboard');
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::view('/', 'admin.dashboard')->name('dashboard');
        Route::view('users', 'admin.users.index')->name('users.index');
        Route::view('users/create', 'admin.users.create')->name('users.create');
        Route::get('users/{user}/edit', fn (\App\Models\User $user) => view('admin.users.edit', compact('user')))->name('users.edit');
        Route::view('items', 'admin.items.index')->name('items.index');
        Route::view('items/create', 'admin.items.create')->name('items.create');
        Route::get('items/{item}/edit', fn (\App\Models\Item $item) => view('admin.items.edit', compact('item')))->name('items.edit');
        Route::view('trades', 'admin.trades.index')->name('trades.index');
    });

    Route::view('inventaris', 'user.inventaris')->name('inventaris');

    Route::view('trades', 'user.trades')->name('trades');

    Route::view('catalogus', 'items.catalog')->name('catalogus');
    Route::get('notifications', [NotificationsController::class, 'index'])->name('notifications');
});

require __DIR__.'/settings.php';
