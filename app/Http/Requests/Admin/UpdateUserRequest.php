<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id; // logged in user id

        return [
            'name'          => 'sometimes|string|max:255',
            'email'         => [
                                'sometimes',
                                'email',
                                Rule::unique('users', 'email')->ignore($userId),
                               ],
            'password'      => 'sometimes|min:8|confirmed',
            'profile_image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'          => 'This email is already taken',
            'email.email'           => 'Please provide valid email',
            'password.min'          => 'Password must be at least 8 characters',
            'password.confirmed'    => 'Password confirmation does not match',
            'profile_image.image'   => 'File must be an image',
            'profile_image.mimes'   => 'Image must be jpg, jpeg or png',
            'profile_image.max'     => 'Image size must not exceed 2MB',
        ];
    }
}
