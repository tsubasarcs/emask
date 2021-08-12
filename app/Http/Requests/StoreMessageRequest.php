<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
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
            'text' => [
                'required',
                'string',
                'exists:shops,code'
            ]
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
            'text.required' => '簡訊內容必須填寫',
            'text.string' => '簡訊內容格式必須是文字',
            'text.exists' => '場所代碼不存在',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $text = $this->input('text');

        if (!empty($text)) {
            $this->merge([
                'text' => preg_replace('/[^0-9]/', '', $text),
            ]);
        }
    }
}
