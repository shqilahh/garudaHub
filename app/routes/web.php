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
// throttle: maksimal 5 percobaan login per menit (anti brute-force)
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Proyek dan penawaran (otorisasi per peran ditambahkan di tahap berikutnya)
Route::resource('projects', ProjectController::class);
Route::post('projects/{project}/bids', [BidController::class, 'store'])->name('bids.store');
Route::patch('bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');
Route::delete('bids/{bid}', [BidController::class, 'destroy'])->name('bids.destroy');
