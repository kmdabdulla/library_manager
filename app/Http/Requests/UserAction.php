<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAction extends FormRequest
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
            'bookId' => 'required|numeric',
            'action' => 'required|string',
        ];
    }

    /*protected function prepareForValidation()
    {
        $this->merge([
            'bookId' => filter_var($this->bookId,FILTER_SANITIZE_NUMBER_INT),
            'action' => filter_var($this->action,FILTER_SANITIZE_STRING),
        ]);
    }*/

    public function messages()
    {
        return [
            'bookId.required' => 'The bookId field is required.',
            'action.required' => 'The action field is required.',
        ];
    }
}
