<?php

namespace App\Http\Requests;

use App\Traits\FormRequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserPostReactionValidationRequest extends FormRequest
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
            'mId'  => 'required',
            'reaction'  => 'required',  
            'mType'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'mId.required' => 'mId is required.!',
            'reaction.required' => 'reaction is required.!',
            'mType.required' => 'mType is required.!',
        ];
    }
}
