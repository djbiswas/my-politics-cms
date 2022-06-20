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
            'type'  => 'required',
            'value'  => 'required',
            'password'  => 'required',  
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'login type is required.!',
            'value.required' => 'login value is required.!',
            'password.required' => 'login password is required.!',
        ];
    }
}
