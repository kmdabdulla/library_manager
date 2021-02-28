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
                'password_confirmation' => 'sometimes|required|same:password',
                'date_of_birth' => 'required|date_format:Y-m-d|before:tomorrow',
            ];

    }

    protected function prepareForValidation()
    {
        $this->merge([
                'email' => filter_var(strtolower($this->email), FILTER_SANITIZE_EMAIL),
            ]);

    }
}
