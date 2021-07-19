<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required = !$this->student ? 'required|' : '';

        return [
            'name' => $required . 'max:255',
            'address' => $required . 'max:255',
            'phone' => $required . 'max:255',
            'specialized' => $required . 'integer',
        ];
    }
}
