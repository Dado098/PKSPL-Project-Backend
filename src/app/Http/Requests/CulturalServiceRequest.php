<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CulturalServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'id_area' => [$required, 'integer', 'exists:area_terdampak,id_area'],
            'nama_aktivitas' => [$required, 'string', 'max:150'],
            'jumlah_pengunjung' => [$required, 'numeric', 'decimal:0,2'],
            'biaya_perjalanan' => [$required, 'numeric', 'decimal:0,2'],
            'frekuensi' => [$required, 'numeric', 'decimal:0,2'],
            'referensi' => ['nullable', 'string'],
            'nilai' => [$required, 'numeric', 'decimal:0,2'],
        ];
    }
}
