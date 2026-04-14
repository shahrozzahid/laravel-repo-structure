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
use App\Http\Requests\Auth\LoginRequest;



class AuthController extends BaseApiController
{
    private $_userService;


    /**
     * RegisteredUserController constructor.
     * @param IUserServiceContract $userService
     */
    public function __construct(IUserServiceContract $userService)
    {
        $this->service = $userService;
    }

     // Register
     
     public function register(StoreUserRequest $request)
     {
 
        try {
         $data = $request->validated();
         $token = Str::random(60); // Generate random token
         $user = $this->service->userStore($data, $token);
         return $this->createdResponse(
            $user,
            $this->getMessage('adminMessages.store_success'),
            $token,
            'Bearer'
        );
         } catch (\Exception $e) {
         return $this->errorResponse($e->getMessage(), 500);
         }
     }

     // Login
     public function login(LoginRequest $request)
     {
        try {
            $data = $request->validated();
            // Step 1 — Attempt authentication FIRST
            if (!Auth::attempt([
                'email'    => $data['email'],
                'password' => $data['password'],
            ])) {
                return $this->notFoundResponse( $this->getMessage('adminMessages.invalid_credentials'), 401);
            }
            $token = Str::random(60); // Generate random token
            $user = Auth::user();
            
            $user->forceFill(['api_token' => $token])->save();
            return $this->successResponse(
                $user,
                $this->getMessage('authMessages.login_success'),
                $token,
                'Bearer'
            );
        }catch (\Exception $e) {
            return $this->notFoundResponse($e->getMessage(), 404);
        }

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
