<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Contracts\IUserServiceContract;
use App\Http\Requests\Admin\StoreUserRequest;

class AuthController extends Controller
{
    private $_userService;


    /**
     * RegisteredUserController constructor.
     * @param IUserServiceContract $userService
     */
    public function __construct(IUserServiceContract $userService)
    {
        $this->_userService = $userService;
    }

     // Register
     public function register(StoreUserRequest $request)
     {
         return $request->all();
 
        try {
         $data = $request->validated();

         $token = Str::random(60); // Generate random token

         $user = $this->_userService->userStore($data, $token);

 
         return response()->json([
             'status'     => true,
             'message'    => 'Registered successfully',
             'api_token'  => $token,
             'token_type' => 'Bearer',
             'user'       => $user,
         ], 201);
         } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Registration failed',
                'errors'  => $e->getMessage(),
            ], 500);
         }
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
