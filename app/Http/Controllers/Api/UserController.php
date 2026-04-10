<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{

public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    return redirect()->intended(route(\App\Helpers\GeneralHelpers::WHO_AM_I(). '.dashboard', absolute: false));
}


/**
     * @param Request $request
     *
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            return GeneralHelper::SEND_RESPONSE(
                $request,
                $this->__userData(Auth::user()),
                null,
                Config::get('constants.generalMessages.first_login')
            );
        }
        return GeneralHelper::SEND_RESPONSE($request, [], null, Config::get('constants.authMessages.invalid_credentials'), Config::get('constants.authMessages.invalid_credentials'));
    }

}