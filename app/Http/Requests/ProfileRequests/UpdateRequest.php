<?php

namespace App\Http\Requests\ProfileRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['sometimes','min:4', 'max:128'],
            'email' => ['sometimes','required', 'email', Rule::unique('users')->ignore($this->id), 'max:120'],
            'surname' => ['sometimes','min:4', 'max:128'],
            'password' => ['sometimes', 'confirmed', 'min:4', 'max:50'],
            'additionalInfo.phone_number' => ['nullable', 'string', 'max:30'],
            'additionalInfo.about_me' => ['nullable', 'string', 'max:300'],
            'additionalInfo.address' => ['nullable', 'string', 'max:150'],
        ];
    }
}
