<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EkosistemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return ['nama_ekosistem' => [$required, 'string', 'max:150'], 'deskripsi' => ['nullable', 'string'], 'status' => [$required, Rule::in(['Aktif', 'Nonaktif'])]];
    }
}
