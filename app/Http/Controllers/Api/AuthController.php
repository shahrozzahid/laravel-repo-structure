<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{
     // Register
     public function register(Request $request)
     {
         $request->validate([
             'name'     => 'required|string|max:255',
             'email'    => 'required|email|unique:users',
             'password' => 'required|min:8|confirmed',
             'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
         ]);
 
         // Handle profile image upload
        $profilePath = null;
        if ($request->hasFile('profile_image')) {
            $profilePath = $request->file('profile_image')->store('profiles', 'public');
        }
         $token = Str::random(60); // Generate random token
 
         $user = User::create([
             'name'      => $request->name,
             'email'     => $request->email,
             'password'  => Hash::make($request->password),
             'api_token' => $token,
             'profile_image' => $profilePath,
         ]);
 
         return response()->json([
             'message'    => 'Registered successfully',
             'api_token'  => $token,
             'token_type' => 'Bearer',
             'user'       => $user,
         ], 201);
     }
 
     // Login
     public function login(Request $request)
     {
         $request->validate([
             'email'    => 'required|email',
             'password' => 'required',
         ]);
 
         if (!Auth::attempt($request->only('email', 'password'))) {
             return response()->json([
                 'message' => 'Invalid credentials',
             ], 401);
         }
 
         // Generate new token on every login
         $token = Str::random(60);
 
         $user = Auth::user();
         $user->forceFill(['api_token' => $token])->save();
 
         return response()->json([
             'message'    => 'Login successful',
             'api_token'  => $token,
             'token_type' => 'Bearer',
             'user'       => $user,
         ]);
     }
 
     // Logout
     public function logout(Request $request)
     {
         // Invalidate token by setting it to null
         $request->user()->forceFill(['api_token' => null])->save();
 
         return response()->json([
             'message' => 'Logged out successfully',
         ]);
     }
 
     // Get authenticated user
     public function me(Request $request)
     {
         return response()->json($request->user());
     }
}
