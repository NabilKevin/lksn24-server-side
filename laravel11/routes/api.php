<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\SetController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\checkAdmin;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::middleware(checkAdmin::class)->group(function() {

        Route::resource('courses', CourseController::class)->except(['index', 'show']);

        Route::resource('lessons', LessonController::class);
    });

    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{slug}', [CourseController::class, 'show']);

    Route::post('/lesson-contents/{id}/check', [LessonController::class, 'checkAnswer']);
    Route::put('/lessons/{id}/complete', [LessonController::class, 'completeLesson']);
    Route::prefix('courses')->group(function() {
        Route::middleware(checkAdmin::class)->group(function() {
            Route::post('/{course}/sets', [SetController::class, 'store']);
            Route::delete('/{course}/sets/{id}', [SetController::class, 'destroy']);
        });
        Route::post('/{slug}/register', [UserController::class, 'register']);
    });
    Route::get('/users/progress', [UserController::class, 'progress']);
});
