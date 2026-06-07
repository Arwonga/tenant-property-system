<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validate what the mobile app sent
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Check the database
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        // 3. Find the user and generate a secure mobile token
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('mobile-app-token')->plainTextToken;

        // 4. Send the token and user data back to the Flutter app
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role, 
            ]
        ]);
    }
}