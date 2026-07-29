<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return ['nama_role' => [$required, 'string', 'max:50', Rule::unique('roles', 'nama_role')->ignore($this->route('role'), 'id_role')], 'deskripsi' => ['nullable', 'string']];
    }
}
