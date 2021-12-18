<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


        $authToken = $user->createToken('auth-token')->plainTextToken;
        return response()->json([
            'access_token' => $authToken,
            'message' => 'user authorized'
        ]);
    }
}
