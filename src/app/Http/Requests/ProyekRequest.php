<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProyekRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'id_user' => [$required, 'integer', 'exists:users,id_user'],
            'nama_proyek' => [$required, 'string', 'max:150'],
            'tujuan_valuasi' => [$required, 'string'],
            'lokasi' => [$required, 'string', 'max:255'],
            'tahun' => [$required, 'integer', 'between:1901,2155'],
            'deskripsi' => ['nullable', 'string'],
            'status' => [$required, Rule::in(['Draft', 'Proses', 'Selesai', 'Dibatalkan'])],
        ];
    }
}
