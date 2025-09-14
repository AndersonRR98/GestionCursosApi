<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('courseusers', CourseUserController::class);
Route::apiResource('lessons', LessonController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('users', UserController::class);





