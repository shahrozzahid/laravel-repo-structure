<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Mail\LoginMail;
use App\Mail\ForgotPasswordMail;


abstract class GeneralHelpers
{


/**
     * Dispatch Mail based on view type
     */
    public static function DISPATCH_MAIL($data)
    {

        try {
            // Select Mail class based on view
            $mailClass = match($data['view']) {
                'register'         => new RegisterMail($data['params']),
                'login'            => new LoginMail($data['params']),
                'forgot_password'  => new ForgotPasswordMail($data['params']),
                default            => null,
            };

            // ✅ Check mail class resolved
        if (!$mailClass) {
            \Log::error('DISPATCH_MAIL: No mail class found for view: ' . $data['view']);
            return false;
        }
        // ✅ Send mail
        Mail::to($data['to'])->send($mailClass);

        \Log::info('DISPATCH_MAIL: Mail sent successfully to ' . $data['to']);

        return true;

        } catch (\Exception $e) {
            \Log::error('Mail Error: ' . $e->getMessage());
            return false;
        }
    }






    /**
     * @param object $file
     * @param string $path
     * @param bool|bool $unlink
     * @param string|null $oldPath
     * @return bool|string
     */

     public static function UPLOAD_FILE(
        object $file, 
        string $path, 
        bool $unlink = false, 
        string $oldPath = null
    ) {
        try {
            // Step 1: Generate unique file name
            $name = self::STR_RANDOM(10) . '-' . time() . '.' . $file->getClientOriginalExtension();
    
            // Step 2: Clean path — remove leading/trailing slashes
            $path = trim($path, '/');
    
            // Step 3: Ensure directory exists inside storage/app/public/
            $storagePath = storage_path('app/public/' . $path);
    
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true); // true = create nested dirs
            }
    
            // Step 4: Store file using Storage facade
            $stored = Storage::disk('public')->putFileAs($path, $file, $name);
    
            if (!$stored) {
                return false;
            }
    
            // Step 5: Build the relative path to save in DB
            $full_image_name = $path . '/' . $name;
    
            // Step 6: Remove old file if needed
            if ($unlink && $oldPath) {
                self::REMOVE_FILE($oldPath);
            }
    
            return $full_image_name;
    
        } catch (\Exception $e) {
            \Log::error('UPLOAD_FILE Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param $length
     * @return mixed
     */

    public static function STR_RANDOM($length)
    {
        return Str::random($length);
    }

    /**
     * @param string $name
     * @return bool
     */

     public static function MAKE_DIR(string $path): bool
     {
         // ✅ Must point to storage/app/public/ — same place Storage facade uses
         $fullPath = storage_path('app/public/' . trim($path, '/'));
     
         if (!file_exists($fullPath)) {
             return mkdir($fullPath, 0755, true); // 0755 = permissions, true = recursive
         }
     
         return true; // already exists
     }

    /**
     * @param string $filepath
     * @return bool
     */
    public static function REMOVE_FILE(string $oldPath = null): bool
{
    if (!$oldPath) return false;

    // Remove from storage/app/public/
    if (Storage::disk('public')->exists($oldPath)) {
        Storage::disk('public')->delete($oldPath);
        return true;
    }

    return false;
}

    /**
     * Identify Current User
     *
     * @param null $user
     *
     * @return mixed|null
     */
    public static function WHO_AM_I($user = null)
    {
        return self::GET_ROLE($user ?? auth()->user());
    }

    /**
     * Get Role
     *
     * @param $user
     *
     * @return string
     */
    public static function GET_ROLE($user)
    {
        if($user->hasRole(IUserRole::ADMIN))
            return IUserRole::ADMIN;

        if($user->hasRole(IUserRole::USER))
            return IUserRole::USER;

        if($user->hasRole(IUserRole::GUEST))
            return IUserRole::GUEST;

        return 'undefined';
    }

    /**
     * Success Response
     *
     * @param $data
     * @param $route
     * @param $sucMsg
     *
     * @return RedirectResponse
     */
    public static function SUCCESS($data, $route, $sucMsg = null)
    {
        $data = [
            'message'    => $sucMsg ?? '',
            'alert_type' => 'success',
            'data'       => $data
        ];

        return $route
            ? redirect()->route($route)->with($data)
            : redirect()->back()->with($data);
    }

    /**
     * Error Response
     *
     * @param $data
     * @param $route
     * @param $errMsg
     *
     * @return RedirectResponse
     */
    public static function ERROR($data, $route, $errMsg = null)
    {
        $data = [
            'message'    => $errMsg ?? self::ERROR_MESSAGE,
            'alert_type' => 'error',
            'data'       => $data
        ];

        return $route
            ? redirect()->route($route)->with($data)
            : redirect()->back()->with($data);
    }

    /**
     * User Request Response
     *
     * @param Request $request
     * @param         $data
     * @param string|null $sucMsg
     * @param string|null $route
     * @param string|null $errMsg
     * @param $totals
     *
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public static function SEND_RESPONSE(Request $request, string $sucMsg = null, string $errMsg = null, $data = null, string $route = null, $totals = 0)
    {
        // Send Api Response
        if($request->ajax())
        {
            return $sucMsg
                ? response()->json([
                    'message'    => $sucMsg,
                    'status'     => true,
                    'alert_type' => 'success',
                    'data'       => $data,
                    'total'      => $totals ?? 0,
                    'url'        => $route ? route($route) : null
                ])
                : response()->json([
                    'message'    => $errMsg ?? 'Something went wrong.',
                    'status'     => false,
                    'alert_type' => 'error',
                    'data'       => $data,
                    'url'        => $route ? route($route) : null
                ]);
        }

        // Send Web Response
        return $sucMsg
            ? self::SUCCESS($data ?? null, $route ?? null, $sucMsg)
            : self::ERROR($data ?? null, $route ?? null, $errMsg);
    }
}
