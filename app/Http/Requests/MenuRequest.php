<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        $required = !$this->menu ? 'required|' : '';

        return [
            'title' => $required . 'max:255',
            'link' => 'max:255',
            'icon' => 'max:255',
            'parent_id' => 'integer|min:0',
            'position' => 'integer|min:0',
            'roles.*' => 'integer|min:0',
        ];
    }
}
