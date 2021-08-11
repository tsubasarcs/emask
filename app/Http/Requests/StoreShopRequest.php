<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Geocoder\Facades\Geocoder;

class StoreShopRequest extends FormRequest
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
            'address' => [
                'required',
                'string',
                'max:255',
            ],
            'geocoder' => [function ($attribute, $value, $fail) {
                if ($value['formatted_address'] === 'result_not_found') {
                    $fail('此地址無法得到座標編碼');
                }
            }],
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
            'address.required' => '地址必須填寫',
            'address.string' => '地址格式必須是文字',
            'address.max' => '超過可接受地址最大長度'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $address = $this->input('address');

        if (!empty($address)) {
            $this->merge([
                'geocoder' => Geocoder::getCoordinatesForAddress($address),
            ]);
        }
    }
}
