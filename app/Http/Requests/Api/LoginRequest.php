<?php

namespace App\Http\Requests\Api;

use App\Http\Helpers\Helper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username'=>'required',
            'password'=>'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Helper::sendError('خطأ في بيانات الدخول',$validator->errors()->getMessageBag()->first());
    }
}
