<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'account' => [
                'required',
                // 'unique:users',
            ],
            'password' => 'required|string|min:6|max:20'
        ];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [
            'account' => '账号',
            'password' => '密码'
        ];
    }
}
