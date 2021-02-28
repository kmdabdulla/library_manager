<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; //Model for this controller
use App\Http\Requests\UserLogin; //This file contains request validation and sanitization logic
use App\Http\Requests\UserRegister; //This file contains request validation and sanitization logic for user registration
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginApiController extends Controller
{
    public function register(UserRegister $request) {
        $credentials = $request->validated();
        if(!preg_match("/^(?=.*?[A-Z])(?=.*?[0-9]).*$/",$credentials['password'])) {
            return response()->json(["message"=>"Password should contain at least 1 capital letter and 1 number"],'422');
        }
        $userexists = User::where('email', $credentials['email'])->first();
        if(!empty($userexists)) {
            return response()->json(["message"=>"User already exists!"],'422');
        }
        $user = new User;
        $user->name = $credentials['name'];
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->date_of_birth = $credentials['date_of_birth'];
        $user->save();
        return response()->json([["message"=>"Registration successful!"],["data",$user]],'200');
    }

    public function login(UserLogin $request) {
        $request->validated();
        if (Auth::attempt($request->only('email','password'))) {
            $api_token = Str::random(80);
            User::where('id', Auth::user()->id)->update(['api_token' => hash('sha256',$api_token)]);
            return response()->json([["message"=>" Login Success!"],["api_token",$api_token]],'200');
        }
            return response()->json(["message"=>"Invalid Credentials!"],'200');
    }

    public function logout() {
        Auth::logout();
        User::where('id', Auth::user()->id)->update(['api_token' => null]);
        return response()->json(["message"=>"Logout Successful!"],'200');
    }
}
