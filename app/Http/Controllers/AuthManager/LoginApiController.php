<?php

namespace App\Http\Controllers\AuthManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; //Model for this controller
use App\Http\Requests\UserLogin; //This file contains request validation and sanitization logic
use App\Http\Requests\UserRegister; //This file contains request validation and sanitization logic for user registration
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\AuthManager\LoginService;

class LoginApiController extends Controller
{
    public function register(UserRegister $request, LoginService $register) {
        $credentials = $request->validated();
        if(!preg_match("/^(?=.*?[A-Z])(?=.*?[0-9]).*$/",$credentials['password'])) {
            return response()->json(["message"=>"The given data was invalid.","errors"=>["password"=>["Password should contain at least 1 capital letter and 1 number."]]],'422');
        }
        $userexists = User::where('email', $credentials['email'])->first();
        if(!empty($userexists)) {
            return response()->json(["message"=>"User already exists."],'200');
        }
        $user = $register->register($credentials);
        return response()->json(["message"=>"Registration successful.","data"=>$user],'200');
    }

    public function login(UserLogin $request) {
        $request->validated();
        if (Auth::attempt($request->only('email','password'))) {
            $api_token = Str::random(80);
            User::where('id', Auth::user()->id)->update(['api_token' => hash('sha256',$api_token)]);
            return response()->json(["message"=>"Login Successful.","api_token"=>$api_token],'200');
        }
            return response()->json(["message"=>"Invalid Credentials."],'200');
    }

    public function logout() {
        User::where('id', Auth::user()->id)->update(['api_token' => null]);
        return response()->json(["message"=>"Logout Successful."],'200');
    }
}
