<?php

namespace App\Http\Requests\Api2;

use App\Support\HelperSupport;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BuyNumberRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'server_id'=>'required|exists:servers,id',
            'country_id'=>'required|exists:countries,id',
            'app_id'=>'required|exists:programs,id',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        HelperSupport::sendError($validator->errors()->getMessageBag()->jsonSerialize(),'خطأ في  الطلب');
    }
}
