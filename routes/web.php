<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleAdmin;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [AdminController::class, 'Index'])->middleware(['auth', 'verified', RoleAdmin::class])->name('dashboard');

Route::post('/createadmin', [AdminController::class, 'Store'])->middleware(['auth', 'verified', RoleAdmin::class])->name('create.admin');
Route::get('/editdmin/${id}', [AdminController::class, 'Edit'])->middleware(['auth', 'verified', RoleAdmin::class])->name('edit.admin');
Route::delete('/deletedmin/${id}', [AdminController::class, 'Delete'])->middleware(['auth', 'verified', RoleAdmin::class])->name('delete.admin');

Route::get('/clientes', [ClienteController::class, 'Index'])->middleware('auth')->name('clientes');
Route::post('/create', [ClienteController::class, 'Store'])->middleware('auth')->name('create.cliente');
Route::get('/edit/${id}', [ClienteController::class, 'Edit'])->middleware('auth')->name('edit.cliente');
Route::delete('/delete/${id}', [ClienteController::class, 'Delete'])->middleware('auth')->name('delete.cliente');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
