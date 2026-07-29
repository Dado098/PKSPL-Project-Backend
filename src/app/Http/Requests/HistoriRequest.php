<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistoriRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return ['id_user' => ['required', 'integer', 'exists:users,id_user'], 'id_proyek' => ['required', 'integer', 'exists:proyek,id_proyek'], 'aktivitas' => ['required', 'string', 'max:255'], 'keterangan' => ['nullable', 'string']];
    }
}
