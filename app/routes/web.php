<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/projects');
Route::resource('projects', ProjectController::class);

Route::post('projects/{project}/bids', [BidController::class, 'store'])->name('bids.store');
Route::patch('bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');
Route::delete('bids/{bid}', [BidController::class, 'destroy'])->name('bids.destroy');
