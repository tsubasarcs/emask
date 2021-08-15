<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchMessageRequest extends FormRequest
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
            'time' => [
                'required',
                'string',
                'date_format:Y-m-d\TH:i:s',
            ],
            'from' => [
                'required',
                'string',
                'regex:/^(\(\+886\))?(0)?9\d{2}[\s.-]?\d{3}[\s.-]?\d{3}$/',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'time.required' => '時間不能為空值',
            'time.string' => '時間格式必須是文字',
            'time.date_format' => '時間格式不符合',
            'from.required' => '手機號碼不能為空值',
            'from.string' => '手機號碼格式必須是文字',
            'from.regex' => '手機號碼格式不符合',
        ];
    }
}
