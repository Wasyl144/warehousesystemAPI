<?php

namespace App\Http\Requests\UserRequests;

use App\Rules\SortingRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('user.index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => ['integer'],
            'search' => ['max:120', 'string', 'nullable'],
            'orderDirection' => ['string', new SortingRule()],
            'perPage' => ['integer'],
        ];
    }
}
