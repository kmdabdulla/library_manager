<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegister extends FormRequest
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
                'email' => 'required|email|max:255',
                'name' => 'required|string|max:255',
                'password' => 'required|min:8',
                'date_of_birth' => 'required|date_format:Y-m-d|before:tomorrow',
            ];

    }

    protected function prepareForValidation()
    {
        $this->merge([
                'email' => filter_var(strtolower($this->email), FILTER_SANITIZE_EMAIL),
            ]);

    }

    public function messages()
    {
        return [
            'isbn.alpha_num' => 'Invalid ISBN-10.',
            'date_of_birth.required' => 'Date of Birth is required.',
            'date_of_birth.date_format' => 'Date of Birth format should be YYYY-MM-DD.',
            'date_of_birth.before' => 'Date of Birth should not be greater than today.',
        ];
    }
}
