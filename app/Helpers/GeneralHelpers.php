<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


abstract class GeneralHelpers
{

    /**
     * @param object $file
     * @param string $path
     * @param bool|bool $unlink
     * @param string|null $oldPath
     * @return bool|string
     */

    public static function UPLOAD_FILE(object $file, string $path, bool $unlink = false, string $oldPath = null){

     $name = self::STR_RANDOM(10).'-'.time() . '.' . $file->getClientOriginalExtension();
        if(self::MAKE_DIR($path))
        {
            Storage::disk('public')->putFileAs($path, $file, $name);
            $full_image_name =  $path . '/' . $name;

            !$unlink ?: self::REMOVE_FILE($oldPath);
            return $full_image_name;
        }

        return false;

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

    public static function MAKE_DIR(string $name): bool
    {
        if (!Storage::disk('public')->exists($name)) {
            Storage::disk('public')->makeDirectory($name);
        }

        return true;
    }

    /**
     * @param string $filepath
     * @return bool
     */
    public static function REMOVE_FILE(string $filepath): bool
    {
        return Storage::disk('public')->delete($filepath);
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
