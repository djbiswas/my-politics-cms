<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPoliticianVoteValidationRequest extends FormRequest
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
            'politicianId'  => 'required',  
            'vote' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'politicianId.required' => 'politician id is required.!',
            'vote.required' => 'vote is required.!'
        ];
    }
}
