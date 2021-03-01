<?php

namespace App\Http\Controllers\AuthManager;
use App\Models\User; //Model for this controller
use Illuminate\Support\Facades\Hash;

class LoginService {

    public function register($credentials) {
        $user = new User;
        $user->name = $credentials['name'];
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->date_of_birth = $credentials['date_of_birth'];
        $user->save();
        return $user;
    }
}
