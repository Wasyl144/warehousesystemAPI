<?php

namespace App\Http\Requests\ItemsRequests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('items.store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:256'],
            'category.id' => ['required', 'integer', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:120'],
            'quantity' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
