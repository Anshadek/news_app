<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//auth routes
Auth::routes();
//check if user is authenticated
Route::middleware(['auth'])->group(function () {
Route::get('/', [ArticleController::class, 'index']);

//article routes
route::resource('article', ArticleController::class);
});

