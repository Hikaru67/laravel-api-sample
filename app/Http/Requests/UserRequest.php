<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $required = ! $this->user ? 'required|' : '';

        return [
            'name' => $required.'max:255',
            'email' => $required.'max:255|unique:users,email,'.($this->user->id ?? 0),
            'password' => $required.'min:6|max:255',
            'roles.*' => 'integer|min:0',
        ];
    }
}
