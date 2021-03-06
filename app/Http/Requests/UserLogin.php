<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLogin extends FormRequest
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
                'email' => 'required|email',
                'password' => 'required',
            ];

    }

    protected function prepareForValidation()
    {
        $this->merge([
                'email' => filter_var(strtolower($this->email), FILTER_SANITIZE_EMAIL),
            ]);

    }

    /*public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'date_of_birth.required' => 'Date of Birth is required',
        ];
    }*/
}
