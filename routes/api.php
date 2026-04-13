<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Your protected routes
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Welcome!']);
    });
});


// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });