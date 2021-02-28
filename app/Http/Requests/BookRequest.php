<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
                'title' => 'required|string|max:255',
                'isbn' => 'required|alpha_num|max:10',
                'publishedDate' => 'required|date_format:Y-m-d|before:tomorrow',
            ];

    }

    protected function prepareForValidation()
    {
        $this->merge([
            'title' => filter_var($this->title,FILTER_SANITIZE_STRING),
            'isbn' => filter_var($this->isbn,FILTER_SANITIZE_STRING),
        ]);
    }

    public function messages()
    {
        return [
            'isbn.alpha_num' => 'Invalid ISBN-10',
            'publishedDate.required' => 'Date of publication is required',
            'publishedDate.date_format' => 'Date of publication format should be YYYY-MM-DD',
            'publishedDate.before' => 'Date of publication should not be greater than today',
        ];
    }
}
