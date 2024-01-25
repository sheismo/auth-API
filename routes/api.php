<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post("v1/register", [UserController::class, 'register']); 
// Route::post("v1/register", ['UserController@register']); 
Route::post('v1/login', [UserController::class,'login']);

Route::get('v1/user/{id}', [UserController::class,'getSingleUser']);
Route::post('v1/verify-user', [UserController::class,'verifyUser']);
Route::post('v1/forgot-password', [ForgotPasswordController::class,'sendForgotPasswordEmail']);
Route::post('v1/reset-password', [ForgotPasswordController::class,'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('v1/users', [UserController::class,'getAllUsers']);
    Route::post('v1/post', [PostController::class,'store']);

    Route::get('v1/all-posts', [PostController::class,'index']);
    Route::get('v1/my-posts', [PostController::class,'getMyPosts']);
    Route::get('v1/post/{postId}', [PostController::class,'show']);

    Route::post('v1/post/{id}/comment', [CommentController::class,'store']);
    Route::post('v1/post/{postId}/like', [LikeController::class,'store']);

    Route::post('v1/user/update', [UserController::class,'update']);
});



