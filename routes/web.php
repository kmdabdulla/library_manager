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
Route::get('login', function () {
    if (Auth::check()) {
        return view('addBook');
    }
    return view('login');
})->name('login');
Route::get('register', function () {
    if (Auth::check()) {
        return view('addBook');
    }
    return view('register');
});
Route::post('registerEmail', [App\Http\Controllers\LoginController::class, 'registerEmail']);
Route::post('emailLogin', [App\Http\Controllers\LoginController::class, 'emailLogin']);
Route::post('userLogout', [App\Http\Controllers\LoginController::class, 'userLogout']);

//Library Manager Routes
Route::view('addBook', 'addBook');
Route::get('listBorrowedBooks',[App\Http\Controllers\BookController::class, 'listBorrowedBooks']);
Route::get('listAvailableBooks',[App\Http\Controllers\BookController::class, 'listAvailableBooks']);
Route::get('listUserActivity',[App\Http\Controllers\BookController::class, 'listUserActivity']);
Route::post('addBookToLibrary',[App\Http\Controllers\BookController::class, 'addBookToLibrary']);
Route::post('changeBookStatus',[App\Http\Controllers\BookController::class, 'changeBookStatus']);
