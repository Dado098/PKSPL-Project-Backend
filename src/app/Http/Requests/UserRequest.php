<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';
        $password = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'id_role' => [$required, 'integer', 'exists:roles,id_role'],
            'nama' => [$required, 'string', 'max:100'],
            'email' => [$required, 'email', 'max:100', Rule::unique('users', 'email')->ignore($this->route('user'), 'id_user')],
            'password' => [$password, 'string', 'max:255'],
            'google_id' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'string', 'max:255'],
            'status' => [$required, Rule::in(['Aktif', 'Nonaktif'])],
        ];
    }
}
