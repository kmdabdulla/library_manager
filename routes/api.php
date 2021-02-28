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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::get('book',[App\Http\Controllers\BookApiController::class, 'listBooks']);
    Route::get('book/{id}',[App\Http\Controllers\BookApiController::class, 'getBookDetails']);
    Route::get('useraction',[App\Http\Controllers\BookApiController::class,'listUserActivity']);
    Route::get('userbooks',[App\Http\Controllers\BookApiController::class,'listUserBorrowedBooks']);
    Route::post('useraction',[App\Http\Controllers\BookApiController::class, 'performUserAction']);
    Route::post('book',[App\Http\Controllers\BookApiController::class, 'addBookToLibrary']);
    Route::post('logout',[App\Http\Controllers\LoginApiController::class, 'logout']);
});

Route::post('register',[App\Http\Controllers\LoginApiController::class, 'register']);
Route::post('login',[App\Http\Controllers\LoginApiController::class, 'login']);







