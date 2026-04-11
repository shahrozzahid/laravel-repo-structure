<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Http\Contracts\IUserServiceContract;
use App\Http\Requests\Admin\StoreUserRequest;

class RegisteredUserController extends Controller
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


    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $this->_userService->userStore($data);
        event(new Registered($user));
        Auth::login($user);

        return redirect(route(\App\Helpers\GeneralHelpers::WHO_AM_I(). '.dashboard', absolute: false));

    }
}
