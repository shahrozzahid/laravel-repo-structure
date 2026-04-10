<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Helpers\IUserRole;


// Auth
Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name(IUserRole::USER.'.dashboard');
Route::get('/profile', [ProfileController::class, 'edit'])->name(IUserRole::USER.'.profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name(IUserRole::USER.'.profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name(IUserRole::USER.'.profile.destroy');