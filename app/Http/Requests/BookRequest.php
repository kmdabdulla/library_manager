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
                'title' => 'required_without:bookId|string|max:255',
                'isbn' => 'required_without:bookId|alpha_num|max:10',
                'published_at' => 'required_without:bookId|date_format:Y-m-d|before:tomorrow',
                'bookId' => 'required_without:title|numeric',
                'changeAction' => 'required_without:title|string',
            ];

    }

    protected function prepareForValidation()
    {
        $this->merge([
            'title' => filter_var($this->title,FILTER_SANITIZE_STRING),
            'isbn' => filter_var($this->isbn,FILTER_SANITIZE_STRING),
            'published_at' => filter_var($this->published_at,FILTER_SANITIZE_STRING),
            'bookId' => filter_var($this->bookId,FILTER_SANITIZE_NUMBER_INT),
            'changeAction' => filter_var($this->changeAction,FILTER_SANITIZE_STRING),
        ]);
    }

    public function messages()
    {
        return [
            'isbn.alpha_num' => 'Invalid ISBN-10',
            'published_at.required_without' => 'Date of publication is required',
            'published_at.date_format' => 'Date of publication format should be YYYY-MM-DD',
            'published_at.before' => 'Date of publication should not be greater than today',
        ];
    }
}
