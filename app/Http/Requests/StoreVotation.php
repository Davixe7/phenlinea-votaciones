<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVotation extends FormRequest
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
          'name' => 'required|string|min:7|max:50',
          'type' => ['required','string', Rule::in(['single', 'multiple'])],
          'total_voters' => 'required|digits_between:2,4',
          'quorum' => 'required|digits_between:1,2',
          'options.*.name'     => 'required|string|min:7|max:50',
        ];
    }
}
