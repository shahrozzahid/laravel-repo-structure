<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Helpers\IUserRole;
 
// Auth

Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name(IUserRole::ADMIN.'.dashboard');
Route::get('/profile', [ProfileController::class, 'edit'])->name(IUserRole::ADMIN.'.profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name(IUserRole::ADMIN.'.profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name(IUserRole::ADMIN.'.profile.destroy');
