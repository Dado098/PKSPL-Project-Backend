<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Memvalidasi data jasa pengaturan dari request API. */
class RegulatingServiceRequest extends FormRequest
{
    /** Mengizinkan request karena otorisasi belum diterapkan. */
    public function authorize(): bool { return true; }

    /** Menetapkan aturan validasi jasa pengaturan. */
    public function rules(): array
    {
        // Menyesuaikan kolom wajib antara pembuatan dan pembaruan data.
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'id_area' => [$required, 'integer', 'exists:area_terdampak,id_area'],
            'jenis_regulating' => [$required, 'string', 'max:150'],
            'indikator' => [$required, 'string', 'max:150'],
            'satuan' => [$required, 'string', 'max:50'],
            'nilai_indikator' => [$required, 'numeric', 'decimal:0,4'],
            'referensi' => ['nullable', 'string'],
            'nilai' => [$required, 'numeric', 'decimal:0,2'],
        ];
    }
}
