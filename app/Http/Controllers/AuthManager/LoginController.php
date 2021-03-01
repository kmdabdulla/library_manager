<?php

namespace App\Http\Controllers\AuthManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLogin; //This file contains request validation and sanitization logic for user login
use App\Http\Requests\UserRegister; //This file contains request validation and sanitization logic for user registration
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User; //Model for this controller
use App\Http\Controllers\AuthManager\LoginService;


class LoginController extends Controller
{
    /**
     * Handles user registration. Check for any existing users and create an account
     * Redirects to dashboard after successfull account creation.
     *
     */
    public function register(UserRegister $request, LoginService $register) {
        $credentials = $request->validated();
        if(!preg_match("/^(?=.*?[A-Z])(?=.*?[0-9]).*$/",$credentials['password'])) {
            return redirect()->back()->withErrors("Password should contain at least 1 capital letter and 1 number.");
        }
        $userexists = User::where('email', $credentials['email'])->first();
        if(!empty($userexists)) {
            return redirect()->back()->withErrors("User already exists.");
        }
        $user = $register->register($credentials);
        Auth::login($user);
        return redirect('addBook');
    }

    /**
     * Handles user login. Check for credentials validity.
     * Redirects to dashboard after successfull login.
     *
     */
    public function login(UserLogin $request) {
        $request->validated();
        if (Auth::attempt($request->only('email','password'))) {
            $request->session()->regenerate();
            return redirect('addBook');
        }
            return redirect()->back()->withErrors('Invalid Credentials.');
    }

    /**
     * Handles user logout. Invalidates the session and regenerates the token
     * Redirects to login screen.
     *
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('emailLogin');
    }
}
