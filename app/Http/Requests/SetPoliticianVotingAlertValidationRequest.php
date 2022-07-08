<?php

namespace App\Http\Requests;

use App\Traits\FormRequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class SetPoliticianVotingAlertValidationRequest extends FormRequest
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
            'politicianId'  => 'required',  
            'alert' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'politicianId.required' => 'politician id is required.!',
            'alert.required' => 'alert is required.!'
        ];
    }
}
