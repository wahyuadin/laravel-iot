<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => 'required|min:5',
            'password' => 'required|min:6',
            'nama'      => 'required'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username Harus diisi',
            'username.min' => 'Username minimal :min karakter',
            'password.min' => 'Password minimal :min karakter',
            'nama.required' => 'Nama harus diisi'
        ];
    }
}
