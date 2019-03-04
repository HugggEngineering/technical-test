<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class HugRequest extends FormRequest
{

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
            'receiver'      => 'phone:GB,mobile',
        ];

    }
}
