<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// stub routes to make sure there is no error


Route::view('/labs', 'labs.index')->name('labs.index');
Route::view('/transactions', 'transactions.index')->name('transactions.index');
Route::view('/users', 'users.index')->name('users.index');
Route::view('/reports', 'reports.index')->name('reports.index');


Route::resource('items', ItemController::class)->middleware(['auth']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';
