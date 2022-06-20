<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserForgotPasswordValidationRequest extends FormRequest
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
            'fieldType'  => 'required',
            'fieldValue'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fieldType.required' => 'Forgot password field type is required.!',
            'fieldValue.required' => 'Forgot password field value is required.!',
        ];
    }
}
