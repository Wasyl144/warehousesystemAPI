<?php

namespace App\Http\Requests\UserRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('user.store');
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
            'password' => ['required', 'confirmed', 'min:4', 'max:50'],
            'additionalInfo.phone_number' => ['nullable', 'string', 'max:30'],
            'additionalInfo.about_me' => ['nullable', 'string', 'max:300'],
            'additionalInfo.address' => ['nullable', 'string', 'max:150'],
            'role.id' => ['nullable', 'integer', 'exists:roles,id']
        ];
    }
}
