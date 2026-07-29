<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaTerdampakRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'id_proyek' => [$required, 'integer', 'exists:proyek,id_proyek'],
            'id_ekosistem' => [$required, 'integer', 'exists:ekosistem,id_ekosistem'],
            'nama_area' => [$required, 'string', 'max:150'],
            'latitude' => [$required, 'numeric', 'decimal:0,6'],
            'longitude' => [$required, 'numeric', 'decimal:0,6'],
            'luas' => [$required, 'numeric', 'decimal:0,2'],
            'satuan_luas' => [$required, 'string', 'max:20'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }
}
