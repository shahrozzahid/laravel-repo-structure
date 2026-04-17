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
use App\Http\Requests\Admin\UpdateUserRequest;



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
     // -----------------------------------------------
    //  user/Register/API
    //  -----------------------------------------------
     
     public function register(StoreUserRequest $request)
     {
 
        try {
         $data = $request->validated();
        //  $token = Str::random(60); // Generate random token
         $user = $this->service->userStore($data);
         return $this->createdResponse(
            $user,
            $this->getMessage('adminMessages.store_success'),
            $user['api_token'],
            'Bearer'
        );
         } catch (\Exception $e) {
         return $this->errorResponse($e->getMessage(), 500);
         }
     }


     // -----------------------------------------------
    //  user/update/API
    //  -----------------------------------------------

    public function update(UpdateUserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = Auth::user();
            // Pass file object separately if exists
            if ($request->hasFile('profile_image')) {
                $data['profile_image'] = $request->file('profile_image');
            }
            // return response()->json(['profile' => $data['profile_image']]);
            $updatedUser = $this->service->userUpdate($user->id, $data);
            return $this->successResponse(
                $updatedUser,
                $this->getMessage('adminMessages.update_success'),
            );

        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage(), 500);
        }
    }

     // -----------------------------------------------
    //  user/Login/API
    //  -----------------------------------------------
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
            // $token = Str::random(60); // Generate random token
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            // $user->forceFill(['api_token' => $token])->save();
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
 
     // -----------------------------------------------
    //  user/Logout/API
    //  -----------------------------------------------
     public function logout(Request $request)
     {
         // Invalidate token by setting it to null
         try {
            //code...
            // $request->user()->forceFill(['api_token' => null])->save();
            $request->user()->currentAccessToken()->delete();

            return $this->successResponse(
            null,
            $this->getMessage('authMessages.logout_success'),
        );
         } catch (\Exception $e) {
            return $this->unauthorizedResponse($e->getMessage());

         }
     }
 
     // -----------------------------------------------
    //  Get authenticated/API
    //  -----------------------------------------------
     public function me(Request $request)
     {
        try {
            //code...
         return $this->successResponse(
            $request->user(),
            $this->getMessage('userMessages.fetch_success'),
        );
        } catch (\Exception $e) {
            return $this->unauthorizedResponse($e->getMessage());
        }
     }
}
