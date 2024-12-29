<?php

namespace App\Http\Requests\Api2;

use App\Support\HelperSupport;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'username'=>'required|unique:users,username',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8',
            'name'=>'required|min:3'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        HelperSupport::sendError($validator->errors()->getMessageBag()->jsonSerialize(),'خطأ في بيانات الدخول');
    }
}
