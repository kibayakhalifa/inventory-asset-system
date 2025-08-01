<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;


Route::get('/', function () {
    return view('welcome');
});

//dashboard route 
Route::get('/dashboard', [DashboardController::class,'index'])
->middleware(['auth','verified'])
->name('dashboard');

//item route
Route::resource('items', ItemController::class);
//transaction route
Route::resource('transactions', TransactionController::class);

//users route
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    Route::put('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
});



// stub routes to make sure there is no error
Route::view('/labs', 'labs.index')->name('labs.index');
Route::view('/reports', 'reports.index')->name('reports.index');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';
