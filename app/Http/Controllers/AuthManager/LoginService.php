<?php

namespace App\Http\Controllers\AuthManager;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService {
    /**
     * This fucntion is used to hash the user password and create an entry in users table.
     *
     */
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
