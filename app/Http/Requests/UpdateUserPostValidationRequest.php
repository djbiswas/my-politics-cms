<?php

namespace App\Http\Requests;

use App\Traits\FormRequestValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPostValidationRequest extends FormRequest
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
            'post_id' => 'required', 
            'politicianId'  => 'required',  
        ];
    }

    public function messages()
    {
        return [
            'post_id.required' => 'post id is required.!',
            'politicianId.required' => 'politician id is required.!',
        ];
    }
}
