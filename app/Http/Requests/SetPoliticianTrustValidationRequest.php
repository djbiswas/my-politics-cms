<?php

namespace App\Http\Requests;

use App\Traits\FormRequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class SetPoliticianTrustValidationRequest extends FormRequest
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
            'respondedId'  => 'required',  
            'trust' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'respondedId.required' => 'responded id is required.!',
            'trust.required' => 'trust is required.!'
        ];
    }
}
