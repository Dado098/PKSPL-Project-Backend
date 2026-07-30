<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Memvalidasi data jasa pendukung dari request API. */
class SupportingServiceRequest extends FormRequest
{
    /** Mengizinkan request karena otorisasi belum diterapkan. */
    public function authorize(): bool { return true; }

    /** Menetapkan aturan validasi jasa pendukung. */
    public function rules(): array
    {
        // Menyesuaikan kolom wajib antara pembuatan dan pembaruan data.
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return ['id_area' => [$required, 'integer', 'exists:area_terdampak,id_area'], 'fungsi_pendukung' => [$required, 'string', 'max:150'], 'deskripsi' => ['nullable', 'string'], 'referensi' => ['nullable', 'string'], 'nilai' => [$required, 'numeric', 'decimal:0,2']];
    }
}
