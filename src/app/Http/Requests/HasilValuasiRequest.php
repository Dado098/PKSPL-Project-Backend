<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Memvalidasi data hasil valuasi dari request API. */
class HasilValuasiRequest extends FormRequest
{
    /** Mengizinkan request karena otorisasi belum diterapkan. */
    public function authorize(): bool { return true; }

    /** Menetapkan aturan validasi komponen nilai valuasi. */
    public function rules(): array
    {
        // Menyesuaikan kolom wajib antara pembuatan dan pembaruan data.
        $required = $this->isMethod('post') ? 'required' : 'sometimes';
        // Menggunakan aturan nominal yang sama untuk seluruh komponen TEV.
        $amount = [$required, 'numeric', 'decimal:0,2'];

        return [
            'id_area' => [$required, 'integer', 'exists:area_terdampak,id_area'],
            'id_metode' => [$required, 'integer', 'exists:metode_valuasi,id_metode'],
            'direct_use_value' => $amount,
            'indirect_use_value' => $amount,
            'option_value' => $amount,
            'existence_value' => $amount,
            'bequest_value' => $amount,
            'tev' => $amount,
            'tanggal_hitung' => [$required, 'date'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
