<?php

use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::get('/quizzes', [QuizController::class, 'getQuizzes'])->name('getQuizzes');
    Route::get('/test', [QuizController::class, 'test'])->name('test');
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser'])->name('user');
});

Route::get('/email/verify/{id}/{hash}', [UserController::class, 'emailVerify'])->middleware('signed')->name('verification.verify');
