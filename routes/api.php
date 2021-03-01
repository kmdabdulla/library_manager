<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('book',[App\Http\Controllers\BookManager\BookApiController::class, 'getBooks']);
    Route::get('book/{id}',[App\Http\Controllers\BookManager\BookApiController::class, 'getBookDetails']);
    Route::get('userbooks',[App\Http\Controllers\BookManager\BookApiController::class,'getUserCheckedOutBooks']);
    Route::post('book',[App\Http\Controllers\BookManager\BookApiController::class, 'addBookToLibrary']);
    Route::get('useraction',[App\Http\Controllers\UserActionManager\UserActionApiController::class,'getUserActions']);
    Route::post('useraction',[App\Http\Controllers\UserActionManager\UserActionApiController::class, 'performUserAction']);
    Route::post('logout',[App\Http\Controllers\AuthManager\LoginApiController::class, 'logout']);
});

Route::post('register',[App\Http\Controllers\AuthManager\LoginApiController::class, 'register']);
Route::post('login',[App\Http\Controllers\AuthManager\LoginApiController::class, 'login']);







