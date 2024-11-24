<?php

use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::get('/quizzes', [QuizController::class, 'getQuizzes'])->name('getQuizzes');
    Route::get('/quiz/{id}', [QuizController::class, 'getQuiz'])->name('getQuiz');
    Route::get('/test', [QuizController::class, 'test'])->name('test');

    Route::get('/email/verify/{id}/{hash}', [UserController::class, 'emailVerify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/resend', [UserController::class, 'emailResend'])->middleware('throttle:6,1');

    Route::post('/password/forgot', [UserController::class, 'passwordResetLink'])->name('password.forgot');
    Route::post('/password/reset', [UserController::class, 'resetPassword'])->name('password.reset');
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser'])->name('user');
    Route::post('/quiz/create', [QuizController::class, 'create'])->name('quiz.create');
});
