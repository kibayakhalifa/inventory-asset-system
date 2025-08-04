<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LabController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;


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
//lab route
Route::resource('labs', LabController::class);


// stub routes to make sure there is no error
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

Route::post('/reports/fetch', [ReportController::class, 'fetch'])->name('reports.fetch');
Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
//test route
Route::get('/test-report-form', function () {
    return view('test-report');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';
