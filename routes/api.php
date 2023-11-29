<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;

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

// Auth routes
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);

// Auth routes with auth:sanctum middleware basic
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('auth/logout', [AuthController::class, 'logout']);
    // Article routes
    Route::get('article/list', [ArticleController::class, 'articleList']);
    Route::post('article/add-bookmark', [ArticleController::class, 'addToBookMark']);
    Route::get('article/get-bookmarked-article', [ArticleController::class, 'getBookmarkedArticles']);
    
});