<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('auth.register');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['nullable', 'max:100'],
            'surname' => ['nullable', 'max:100'],
            'email' => ['required', 'unique:users', 'min:5', 'max:128', 'email'],
            'password' => ['required', 'confirmed', 'min:4', 'max:50']
        ];
    }
}
