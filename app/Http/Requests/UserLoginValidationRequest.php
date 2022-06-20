<?php

namespace App\Http\Requests;

use App\Traits\FormRequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserLoginValidationRequest extends FormRequest
{
    use FormRequestValidationTrait;

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
            'fieldType.required' => 'login field type is required.!',
            'fieldValue.required' => 'login field value is required.!',
            'password.required' => 'login password is required.!',
        ];
    }
}
