<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/projects');

// Autentikasi
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Publik: lihat proyek
Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');

// Khusus KLIEN (harus login + peran client)
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::patch('bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');
});

// Publik: detail proyek (harus setelah projects/create)
Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

// Khusus DEVELOPER
Route::middleware(['auth', 'role:developer'])->group(function () {
    Route::post('projects/{project}/bids', [BidController::class, 'store'])->name('bids.store');
    Route::delete('bids/{bid}', [BidController::class, 'destroy'])->name('bids.destroy');
});
