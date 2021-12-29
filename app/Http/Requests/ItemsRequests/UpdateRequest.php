<?php

namespace App\Http\Requests\ItemsRequests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('items.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['sometimes', 'string', 'max:256'],
            'id_category' => ['sometimes', 'integer', 'exists:categories,id'],
            'location' => ['sometimes', 'string', 'max:120'],
            'quantity' => ['sometimes', 'integer'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
