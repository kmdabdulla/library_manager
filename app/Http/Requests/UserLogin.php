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
                'email' => 'required|email|max:255',
                'name' => 'required_with:password_confirmation|string|max:255',
                'password' => 'required',
                'password_confirmation' => 'sometimes|required|same:password',
                'date_of_birth' => 'sometimes|required_with:password_confirmation|date_format:Y-m-d|before:tomorrow',
            ];

    }

    protected function prepareForValidation()
    {
        $this->merge([
            'email' => filter_var(strtolower($this->email), FILTER_SANITIZE_EMAIL),
            'name' => filter_var($this->name,FILTER_SANITIZE_STRING),
            'date_of_birth' => filter_var($this->date_of_birth,FILTER_SANITIZE_STRING),
        ]);
    }

    public function messages()
    {
        return [
            'name.required_with' => 'Name is required.',
            'date_of_birth.required_with' => 'Date od Birth is required',
        ];
    }
}
