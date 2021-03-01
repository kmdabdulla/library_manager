<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Top level Route
Route::get('/', function () {
    if (Auth::check()) {
        return view('addBook');
    }
    return view('login');
});

//Authentication Routes
Route::get('emailLogin', function () {
    if (Auth::check()) {
        return view('addBook');
    }
    return view('login');
})->name('login');
Route::get('registerEmail', function () {
    if (Auth::check()) {
        return view('addBook');
    }
    return view('register');
});
Route::post('register', [App\Http\Controllers\AuthManager\LoginController::class, 'register']);
Route::post('login', [App\Http\Controllers\AuthManager\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\AuthManager\LoginController::class, 'logout']);

//Library Manager Book Routes
Route::view('addBook', 'addBook');
Route::get('getUserCheckedOutBooks',[App\Http\Controllers\BookManager\BookController::class, 'getUserCheckedOutBooks']);
Route::get('getAvailableBooks',[App\Http\Controllers\BookManager\BookController::class, 'getAvailableBooks']);
Route::post('addBookToLibrary',[App\Http\Controllers\BookManager\BookController::class, 'addBookToLibrary']);

//Library Manager User Action Routes
Route::get('getUserActions',[App\Http\Controllers\UserActionManager\UserActionController::class, 'getUserActions']);
Route::post('performUserAction',[App\Http\Controllers\UserActionManager\UserActionController::class, 'performUserAction']);
