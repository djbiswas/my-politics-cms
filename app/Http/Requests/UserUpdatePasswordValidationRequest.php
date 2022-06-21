<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdatePasswordValidationRequest extends FormRequest
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
            'password'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fieldType.required' => 'Field type is required.!',
            'fieldValue.required' => 'Field value is required.!',
            'password.required' => 'Password is required.!',
        ];
    }
}
